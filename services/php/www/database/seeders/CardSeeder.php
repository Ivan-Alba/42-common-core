<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Enums\CardRarity;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds for the Nexus Nine card catalog.
     */
    public function run(): void
    {
        $cards = [
            // --- CATEGORY: HUMAN ---
            [
                'id' => 1,
                'name' => 'Grand Inquisitor',
                'top' => 5, 'right' => 3, 'bottom' => 4, 'left' => 3,
                'rarity' => CardRarity::COMMON,
                'description' => 'A relentless judge of truth and heresy. Seeking forbidden knowledge.',
                'front_image' => null,
            ],
            [
                'id' => 2,
                'name' => 'Sage of Mana',
                'top' => 2, 'right' => 6, 'bottom' => 2, 'left' => 6,
                'rarity' => CardRarity::COMMON,
                'description' => 'A peaceful wanderer carrying the wisdom of centuries. Offers immense magical energy.',
                'front_image' => null,
            ],
            [
                'id' => 3,
                'name' => 'Dragon Sorceress',
                'top' => 7, 'right' => 4, 'bottom' => 2, 'left' => 6,
                'rarity' => CardRarity::RARE,
                'description' => 'Channeling the fury of drakes, she masters spells and scales with burning rage.',
                'front_image' => null,
            ],
            [
                'id' => 4,
                'name' => 'Mana Alchemist',
                'top' => 3, 'right' => 5, 'bottom' => 7, 'left' => 4,
                'rarity' => CardRarity::RARE,
                'description' => 'Distills the world\'s energy into potent elixirs, transforming liquids into miracles.',
                'front_image' => null,
            ],
            [
                'id' => 5,
                'name' => 'Rune Sorceress',
                'top' => 8, 'right' => 2, 'bottom' => 7, 'left' => 6,
                'rarity' => CardRarity::EPIC,
                'description' => 'Manipulates the battlefield with glowing sigils, unleashing elemental chaos.',
                'front_image' => null,
            ],
            [
                'id' => 6,
                'name' => 'Runic Wizard',
                'top' => 10, 'right' => 4, 'bottom' => 8, 'left' => 5,
                'rarity' => CardRarity::GOLDEN,
                'description' => 'Ancient master of the arcane arts. Alters reality and turns the tide of wars.',
                'front_image' => null,
            ],

            // --- CATEGORY: ANIMALS ---
            [
                'id' => 7,
                'name' => 'Bronze Owl',
                'top' => 6, 'right' => 2, 'bottom' => 5, 'left' => 3,
                'rarity' => CardRarity::COMMON,
                'description' => 'Ancient mechanical guardian watching the skies with relentless precision.',
                'front_image' => null,
            ],
            [
                'id' => 8,
                'name' => 'Iron Crow',
                'top' => 4, 'right' => 5, 'bottom' => 3, 'left' => 5,
                'rarity' => CardRarity::COMMON,
                'description' => 'Mechanical scavenger collecting glowing secrets and bringing dark omens.',
                'front_image' => null,
            ],
            [
                'id' => 9,
                'name' => 'Shadow Wolf',
                'top' => 3, 'right' => 4, 'bottom' => 6, 'left' => 2,
                'rarity' => CardRarity::COMMON,
                'description' => 'Phantom beast of dark nightmares hunting under the moonlight.',
                'front_image' => null,
            ],
            [
                'id' => 10,
                'name' => 'Mechanical Spider',
                'top' => 5, 'right' => 7, 'bottom' => 4, 'left' => 3,
                'rarity' => CardRarity::RARE,
                'description' => 'Terror of gears and poisonous precision weaving invisible steel webs.',
                'front_image' => null,
            ],
            [
                'id' => 11,
                'name' => 'Crystal Snake',
                'top' => 2, 'right' => 8, 'bottom' => 5, 'left' => 8,
                'rarity' => CardRarity::EPIC,
                'description' => 'Predator of glowing caves whose venom turns blood into solid crystal.',
                'front_image' => null,
            ],
            [
                'id' => 12,
                'name' => 'Smoke Panther',
                'top' => 6, 'right' => 9, 'bottom' => 3, 'left' => 10,
                'rarity' => CardRarity::GOLDEN,
                'description' => 'Elusive feline born from ashes leaving only a trail of smoke and devastation.',
                'front_image' => null,
            ],

            // --- CATEGORY: BEASTS ---
            [
                'id' => 13,
                'name' => 'Stone Gargoyle',
                'top' => 2, 'right' => 7, 'bottom' => 2, 'left' => 4,
                'rarity' => CardRarity::COMMON,
                'description' => 'Animated statue of unshakable granite resolve protecting sacred grounds.',
                'front_image' => null,
            ],
            [
                'id' => 14,
                'name' => 'Iron Colossus',
                'top' => 6, 'right' => 6, 'bottom' => 6, 'left' => 2,
                'rarity' => CardRarity::RARE,
                'description' => 'Indestructible giant of enchanted metal, ultimate bastion of ancient defense.',
                'front_image' => null,
            ],
            [
                'id' => 15,
                'name' => 'Soul Forger',
                'top' => 4, 'right' => 3, 'bottom' => 8, 'left' => 5,
                'rarity' => CardRarity::RARE,
                'description' => 'Underworld blacksmith hammering spirits into weapons of cold steel.',
                'front_image' => null,
            ],
            [
                'id' => 16,
                'name' => 'Arcane Dragon',
                'top' => 8, 'right' => 6, 'bottom' => 2, 'left' => 8,
                'rarity' => CardRarity::EPIC,
                'description' => 'Creature of primordial fires controlling the elements with absolute devastation.',
                'front_image' => null,
            ],
            [
                'id' => 17,
                'name' => 'Obsidian Sentinel',
                'top' => 4, 'right' => 8, 'bottom' => 8, 'left' => 4,
                'rarity' => CardRarity::EPIC,
                'description' => 'Watcher of volcanic stone awakening to the ruin of ancient kingdoms.',
                'front_image' => null,
            ],
            [
                'id' => 18,
                'name' => 'Runic Leviathan',
                'top' => 8, 'right' => 10, 'bottom' => 5, 'left' => 7,
                'rarity' => CardRarity::GOLDEN,
                'description' => 'Titan of the deep inscribed with runes whose roar dominates the ocean.',
                'front_image' => null,
            ],

            // --- CATEGORY: ARTIFACTS ---
            [
                'id' => 19,
                'name' => 'Mana Shield',
                'top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4,
                'rarity' => CardRarity::COMMON,
                'description' => 'Barrier of pure arcane threads absorbing devastating magical impacts.',
                'front_image' => null,
            ],
            [
                'id' => 20,
                'name' => 'Wandering Core',
                'top' => 5, 'right' => 5, 'bottom' => 1, 'left' => 5,
                'rarity' => CardRarity::COMMON,
                'description' => 'Sentient sphere of unstable magic discharging volatile energy.',
                'front_image' => null,
            ],
            [
                'id' => 21,
                'name' => 'Compass of Shadows',
                'top' => 1, 'right' => 7, 'bottom' => 7, 'left' => 4,
                'rarity' => CardRarity::RARE,
                'description' => 'Cursed artifact guiding the bearer toward their deepest fears.',
                'front_image' => null,
            ],
            [
                'id' => 22,
                'name' => 'Runic Sword',
                'top' => 7, 'right' => 2, 'bottom' => 5, 'left' => 6,
                'rarity' => CardRarity::RARE,
                'description' => 'Glowing blade cutting through armor and granting strength of fallen heroes.',
                'front_image' => null,
            ],
            [
                'id' => 23,
                'name' => 'Orb of Vision',
                'top' => 7, 'right' => 7, 'bottom' => 4, 'left' => 6,
                'rarity' => CardRarity::EPIC,
                'description' => 'Sphere of starlight revealing hidden enemies and secrets of the past.',
                'front_image' => null,
            ],
            [
                'id' => 24,
                'name' => 'Chalice of Energy',
                'top' => 10, 'right' => 3, 'bottom' => 10, 'left' => 3,
                'rarity' => CardRarity::GOLDEN,
                'description' => 'Sacred vessel granting unimaginable strength and eternal vitality.',
                'front_image' => null,
            ],
        ];

        foreach ($cards as $card) {
            Card::updateOrCreate(['id' => $card['id']], $card);
        }
    }
}
