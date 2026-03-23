<?php

namespace App\Services;

use App\Models\ActiveMatch;
use App\Models\Card;
use Illuminate\Support\Facades\Log;

/**
 * Class MatchLogicEngine
 * Handles the authoritative game logic for card battles.
 * Adjusted to map 'up' and 'down' directions to 'top' and 'bottom' database columns.
 */
class MatchLogicEngine
{
    /**
     * Internal direction logic names.
     */
    private static $directions = ['up', 'right', 'down', 'left'];

    /**
     * Map for value comparison between touching card faces.
     */
    private static $oppositeDirections = [
        'up' => 'down',
        'down' => 'up',
        'left' => 'right',
        'right' => 'left'
    ];

    /**
     * Maps internal direction names to actual database column names in the 'cards' table.
     */
    private static $dbColumnMap = [
        'up' => 'top',
        'down' => 'bottom',
        'left' => 'left',
        'right' => 'right'
    ];

    public static function calculateMove(ActiveMatch $match, array &$board, int $placedIndex, int $playerId)
    {
        $steps = [];
        $flippedGlobal = [];

        $config = $match->getMatchConfigAttribute();
        $activeRules = $config['rules'] ?? [];

        $rulesEnabled = [
            'plus' => in_array('plus', $activeRules),
            'same' => in_array('same', $activeRules),
            'combo' => in_array('combo', $activeRules)
        ];

        if ($rulesEnabled['plus'] || $rulesEnabled['same']) {
            self::processSpecialRules($match, $board, $placedIndex, $playerId, $steps, $flippedGlobal, $rulesEnabled);
        }

        self::processNormalCaptures($board, $placedIndex, $playerId, $steps, $flippedGlobal);

        return [
            'steps' => $steps,
            'new_board' => $board
        ];
    }

    private static function processSpecialRules($match, &$board, $placedIndex, $playerId, &$steps, &$flippedGlobal, $rules)
    {
        $attackerData = self::getCardData($board[$placedIndex]['card_id']);
        if (!$attackerData)
            return;

        $sideValues = [];
        $neighbors = [];

        foreach (self::$directions as $dir) {
            $nIndex = self::getNeighborIndex($placedIndex, $dir);
            if ($nIndex !== null && isset($board[$nIndex]) && $board[$nIndex] !== null) {
                $neighbors[] = $nIndex;
                $dbCol = self::$dbColumnMap[$dir];
                $sideValues[$nIndex] = (int) $attackerData['attributes'][$dbCol];
            }
        }

        if (count($neighbors) < 2)
            return;

        $involvedInSpecial = [];
        $triggeredRule = "";

        if ($rules['plus']) {
            $plusGroups = collect($neighbors)->groupBy(function ($nIdx) use ($board, $sideValues, $placedIndex) {
                $neighborCard = self::getCardData($board[$nIdx]['card_id']);
                $dirToNeighbor = self::getDirectionOfNeighbor($placedIndex, $nIdx);
                $oppDir = self::$oppositeDirections[$dirToNeighbor];
                $oppDbCol = self::$dbColumnMap[$oppDir];
                return $sideValues[$nIdx] + (int) $neighborCard['attributes'][$oppDbCol];
            })->filter(fn($g) => $g->count() >= 2);

            foreach ($plusGroups as $group) {
                if ($group->contains(fn($nIdx) => (int) $board[$nIdx]['owner_id'] !== (int) $playerId)) {
                    foreach ($group as $idx)
                        $involvedInSpecial[] = $idx;
                    $triggeredRule = "plus";
                }
            }
        }

        if ($rules['same']) {
            $sameGroups = collect($neighbors)->filter(function ($nIdx) use ($board, $sideValues, $placedIndex) {
                $neighborCard = self::getCardData($board[$nIdx]['card_id']);
                $dirToNeighbor = self::getDirectionOfNeighbor($placedIndex, $nIdx);
                $oppDir = self::$oppositeDirections[$dirToNeighbor];
                $oppDbCol = self::$dbColumnMap[$oppDir];
                return $sideValues[$nIdx] == (int) $neighborCard['attributes'][$oppDbCol];
            });

            if ($sameGroups->count() >= 2 && $sameGroups->contains(fn($nIdx) => (int) $board[$nIdx]['owner_id'] !== (int) $playerId)) {
                foreach ($sameGroups as $idx)
                    $involvedInSpecial[] = $idx;
                $triggeredRule = ($triggeredRule === "plus") ? "plus_same" : "same";
            }
        }

        if (!empty($involvedInSpecial)) {
            $involvedInSpecial = array_unique($involvedInSpecial);
            $actualFlips = array_filter($involvedInSpecial, fn($idx) => (int) $board[$idx]['owner_id'] !== (int) $playerId);

            if (!empty($actualFlips)) {
                $steps[] = [
                    'rule' => $triggeredRule,
                    'card_indices' => array_values($actualFlips),
                    'involved_indices' => array_values($involvedInSpecial),
                    'caused_by_index' => $placedIndex
                ];

                foreach ($actualFlips as $idx) {
                    $board[$idx]['owner_id'] = $playerId;
                    $flippedGlobal[] = $idx;
                }

                if ($rules['combo']) {
                    foreach ($actualFlips as $flippedIdx) {
                        self::processComboRecursive($board, $flippedIdx, $playerId, $steps, $flippedGlobal);
                    }
                }
            }
        }
    }

    private static function processComboRecursive(&$board, $centerIndex, $playerId, &$steps, &$flippedGlobal)
    {
        $centerCardData = self::getCardData($board[$centerIndex]['card_id']);
        $comboCaptures = [];

        foreach (self::$directions as $dir) {
            $nIndex = self::getNeighborIndex($centerIndex, $dir);
            if ($nIndex === null || in_array($nIndex, $flippedGlobal))
                continue;

            $neighbor = $board[$nIndex] ?? null;
            if ($neighbor && (int) $neighbor['owner_id'] !== (int) $playerId) {
                $neighborCardData = self::getCardData($neighbor['card_id']);
                if (self::compareValues($centerCardData, $neighborCardData, $dir)) {
                    $comboCaptures[] = $nIndex;
                }
            }
        }

        if (!empty($comboCaptures)) {
            $steps[] = [
                'rule' => 'combo',
                'card_indices' => $comboCaptures,
                'involved_indices' => $comboCaptures,
                'caused_by_index' => $centerIndex
            ];

            foreach ($comboCaptures as $idx) {
                $board[$idx]['owner_id'] = $playerId;
                $flippedGlobal[] = $idx;
            }

            foreach ($comboCaptures as $idx) {
                self::processComboRecursive($board, $idx, $playerId, $steps, $flippedGlobal);
            }
        }
    }

    private static function processNormalCaptures(&$board, $placedIndex, $playerId, &$steps, &$flippedGlobal)
    {
        $captures = [];
        $attackerCardData = self::getCardData($board[$placedIndex]['card_id']);

        foreach (self::$directions as $dir) {
            $nIndex = self::getNeighborIndex($placedIndex, $dir);
            if ($nIndex === null || in_array($nIndex, $flippedGlobal))
                continue;

            $neighbor = $board[$nIndex] ?? null;
            if ($neighbor && (int) $neighbor['owner_id'] !== (int) $playerId) {
                $neighborCardData = self::getCardData($neighbor['card_id']);
                if (self::compareValues($attackerCardData, $neighborCardData, $dir)) {
                    $captures[] = $nIndex;
                    $board[$nIndex]['owner_id'] = $playerId;
                }
            }
        }

        if (!empty($captures)) {
            $steps[] = [
                'rule' => 'normal',
                'card_indices' => $captures,
                'involved_indices' => $captures,
                'caused_by_index' => $placedIndex
            ];
        }
    }

    private static function compareValues($atkCard, $defCard, $dir)
    {
        if (!$atkCard || !$defCard)
            return false;

        $oppDir = self::$oppositeDirections[$dir];

        $atkDbCol = self::$dbColumnMap[$dir];
        $defDbCol = self::$dbColumnMap[$oppDir];

        $atkVal = (int) $atkCard['attributes'][$atkDbCol];
        $defVal = (int) $defCard['attributes'][$defDbCol];

        return $atkVal > $defVal;
    }

    private static function getNeighborIndex($index, $direction)
    {
        $row = (int) ($index / 3);
        $col = $index % 3;

        switch ($direction) {
            case 'up':
                return ($row < 2) ? $index + 3 : null;
            case 'down':
                return ($row > 0) ? $index - 3 : null;
            case 'left':
                return ($col > 0) ? $index - 1 : null;
            case 'right':
                return ($col < 2) ? $index + 1 : null;
        }
        return null;
    }

    private static function getDirectionOfNeighbor($center, $neighbor)
    {
        foreach (self::$directions as $dir) {
            if (self::getNeighborIndex($center, $dir) === $neighbor)
                return $dir;
        }
        return 'up';
    }

    private static function getCardData($cardId)
    {
        static $cache = [];
        if (!isset($cache[$cardId])) {
            $card = Card::find($cardId);
            if ($card) {
                $cache[$cardId] = [
                    'id' => $card->id,
                    'attributes' => $card->toArray()
                ];
            } else {
                return null;
            }
        }
        return $cache[$cardId];
    }
}
