<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Card;
use App\Enums\CardRarity;
use App\Enums\CardCategory;

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
                'description' => 'A ruthless judge of truth and heresy. He seeks out forbidden knowledge, purging the realm of dark magic with an iron fist and absolute resolve.',
                'category' => CardCategory::HUMAN,
                'blue_artwork' => "GrandInquisitor",
                'red_artwork' => "GrandInquisitor_red"
            ],
            [
                'id' => 2,
                'name' => 'Sage of Mana',
                'top' => 2, 'right' => 6, 'bottom' => 2, 'left' => 6,
                'rarity' => CardRarity::COMMON,
                'description' => 'A peaceful wanderer carrying the wisdom of centuries. He restores balance to the world, offering quiet guidance and immense magical energy to worthy noble champions.',
                'category' => CardCategory::HUMAN,
                'blue_artwork' => "SageOfMana",
                'red_artwork' => "SageOfMana_red"
            ],
            [
                'id' => 3,
                'name' => 'Dragon Sorceress',
                'top' => 7, 'right' => 4, 'bottom' => 2, 'left' => 6,
                'rarity' => CardRarity::RARE,
                'description' => 'Channeling the fury of ancient drakes, she commands both spell and scale, blending destructive arcane magic with the primal, burning wrath of legendary winged beasts.',
                'category' => CardCategory::HUMAN,
                'blue_artwork' => "DragonSorceress",
                'red_artwork' => "DragonSorceress_red"
            ],
            [
                'id' => 4,
                'name' => 'Mana Alchemist',
                'top' => 3, 'right' => 5, 'bottom' => 7, 'left' => 4,
                'rarity' => CardRarity::RARE,
                'description' => 'A brilliant mind obsessed with magical essence. He distills the world\'s raw energy into potent elixirs, transforming simple liquids into miracles of unimaginable mystical power.',
                'category' => CardCategory::HUMAN,
                'blue_artwork' => "ManaAlchemist",
                'red_artwork' => "ManaAlchemist_red"
            ],
            [
                'id' => 5,
                'name' => 'Rune Sorceress',
                'top' => 8, 'right' => 2, 'bottom' => 7, 'left' => 6,
                'rarity' => CardRarity::EPIC,
                'description' => 'Mastering the forgotten symbols of power, she manipulates the battlefield with glowing seals, unleashing spectacular elemental chaos upon anyone foolish enough to cross her path.',
                'category' => CardCategory::HUMAN,
                'blue_artwork' => "RuneSorceress",
                'red_artwork' => "RuneSorceress_red"
            ],
            [
                'id' => 6,
                'name' => 'Runic Wizard',
                'top' => 10, 'right' => 4, 'bottom' => 8, 'left' => 5,
                'rarity' => CardRarity::LEGENDARY,
                'description' => 'An elder master of the arcane arts. He reads the universe\'s fabric, casting complex spells that alter reality and turn the tide of great wars.',
                'category' => CardCategory::HUMAN,
                'blue_artwork' => "RunicWizard",
                'red_artwork' => "RunicWizard_red"
            ],

            // --- CATEGORY: ANIMALS ---
            [
                'id' => 7,
                'name' => 'Bronze Owl',
                'top' => 6, 'right' => 2, 'bottom' => 5, 'left' => 3,
                'rarity' => CardRarity::COMMON,
                'description' => 'An ancient guardian crafted by forgotten mechanics, it watches the skies with relentless precision, recording every hidden secret within the darkest corners of time.',
                'category' => CardCategory::ANIMAL,
                'blue_artwork' => "BronzeOwl",
                'red_artwork' => "BronzeOwl_red"
            ],
            [
                'id' => 8,
                'name' => 'Iron Crow',
                'top' => 4, 'right' => 5, 'bottom' => 3, 'left' => 5,
                'rarity' => CardRarity::COMMON,
                'description' => 'Forged from discarded armor, this mechanical scavenger spies from the gray clouds, collecting shiny secrets and bringing dark omens to the forgotten battlefield below.',
                'category' => CardCategory::ANIMAL,
                'blue_artwork' => "IronCrow",
                'red_artwork' => "IronCrow_red"
            ],
            [
                'id' => 9,
                'name' => 'Shadow Wolf',
                'top' => 3, 'right' => 4, 'bottom' => 6, 'left' => 2,
                'rarity' => CardRarity::COMMON,
                'description' => 'A phantom beast formed from the darkest nightmares. It hunts relentlessly under the moonlight, vanishing into the mist before its terrified prey even senses danger.',
                'category' => CardCategory::ANIMAL,
                'blue_artwork' => "ShadowWolf",
                'red_artwork' => "ShadowWolf_red"
            ],
            [
                'id' => 10,
                'name' => 'Mechanical Spider',
                'top' => 5, 'right' => 7, 'bottom' => 4, 'left' => 3,
                'rarity' => CardRarity::RARE,
                'description' => 'An intricate terror built with clockwork gears and venomous precision. It weaves invisible steel webs, trapping the unwary before striking with lethal, calculated mechanical grace.',
                'category' => CardCategory::ANIMAL,
                'blue_artwork' => "MechanicalSpider",
                'red_artwork' => "MechanicalSpider_red"
            ],
            [
                'id' => 11,
                'name' => 'Crystal Snake',
                'top' => 2, 'right' => 8, 'bottom' => 5, 'left' => 8,
                'rarity' => CardRarity::EPIC,
                'description' => 'Lurking silently in the glowing caves, this beautiful yet deadly predator strikes with piercing venom, turning the blood of its unfortunate victims into solid glass.',
                'category' => CardCategory::ANIMAL,
                'blue_artwork' => "CrystalSnake",
                'red_artwork' => "CrystalSnake_red"
            ],
            [
                'id' => 12,
                'name' => 'Smoke Panther',
                'top' => 6, 'right' => 9, 'bottom' => 3, 'left' => 10,
                'rarity' => CardRarity::LEGENDARY,
                'description' => 'Born from the ashes of burned forests, this elusive feline strikes with blinding speed. It leaves only a trail of dark smoke and silent devastation.',
                'category' => CardCategory::ANIMAL,
                'blue_artwork' => "SmokePanther",
                'red_artwork' => "SmokePanther_red"
            ],

            // --- CATEGORY: BEASTS ---
            [
                'id' => 13,
                'name' => 'Stone Gargoyle',
                'top' => 2, 'right' => 7, 'bottom' => 2, 'left' => 4,
                'rarity' => CardRarity::COMMON,
                'description' => 'An animated statue bound to ancient cathedrals. It protects holy grounds from dark intruders, swooping down with crushing weight and unbreakable, cold, stony granite resolve.',
                'category' => CardCategory::BEAST,
                'blue_artwork' => "StoneGargoyle",
                'red_artwork' => "StoneGargoyle_red"
            ],
            [
                'id' => 14,
                'name' => 'Iron Colossus',
                'top' => 6, 'right' => 6, 'bottom' => 6, 'left' => 2,
                'rarity' => CardRarity::RARE,
                'description' => 'A towering monument of enchanted metal. This indestructible juggernaut crushes entire armies under its heavy steps, serving as the ultimate bastion of ancient earthly defense.',
                'category' => CardCategory::BEAST,
                'blue_artwork' => "IronColossus",
                'red_artwork' => "IronColossus_red"
            ],
            [
                'id' => 15,
                'name' => 'Soul Forger',
                'top' => 4, 'right' => 3, 'bottom' => 8, 'left' => 5,
                'rarity' => CardRarity::RARE,
                'description' => 'A mysterious blacksmith dwelling in the underworld. He hammers the spirits of the dead into powerful, eternal weapons, binding mortal courage to cold, unforgiving steel.',
                'category' => CardCategory::BEAST,
                'blue_artwork' => "SoulForger",
                'red_artwork' => "SoulForger_red"
            ],
            [
                'id' => 16,
                'name' => 'Arcane Dragon',
                'top' => 8, 'right' => 6, 'bottom' => 2, 'left' => 8,
                'rarity' => CardRarity::EPIC,
                'description' => 'Forged in the primordial fires of magic, this ancient creature controls the raw elements, bringing absolute devastation to those who challenge its boundless power.',
                'category' => CardCategory::BEAST,
                'blue_artwork' => "ArcaneDragon",
                'red_artwork' => "ArcaneDragon_red"
            ],
            [
                'id' => 17,
                'name' => 'Obsidian Sentinel',
                'top' => 4, 'right' => 8, 'bottom' => 8, 'left' => 4,
                'rarity' => CardRarity::EPIC,
                'description' => 'Carved from the darkest volcanic stone, this silent watcher stands eternal guard. It awakens only when ancient realms face absolute ruin and inevitable fiery destruction.',
                'category' => CardCategory::BEAST,
                'blue_artwork' => "ObsidianSentinel",
                'red_artwork' => "ObsidianSentinel_red"
            ],
            [
                'id' => 18,
                'name' => 'Runic Leviathan',
                'top' => 8, 'right' => 10, 'bottom' => 5, 'left' => 7,
                'rarity' => CardRarity::LEGENDARY,
                'description' => 'A monstrous titan of the deep, inscribed with glowing ancient runes. Its massive roar commands the ocean, sinking mighty fleets into the dark, watery abyss.',
                'category' => CardCategory::BEAST,
                'blue_artwork' => "RunicLeviathan",
                'red_artwork' => "RunicLeviathan_red"
            ],

            // --- CATEGORY: ARTIFACTS ---
            [
                'id' => 19,
                'name' => 'Mana Shield',
                'top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4,
                'rarity' => CardRarity::COMMON,
                'description' => 'Woven from pure arcane threads, this barrier absorbs devastating magical impacts, protecting the brave bearer by turning incoming destruction into harmless bursts of radiant light.',
                'category' => CardCategory::ARTIFACT,
                'blue_artwork' => "ManaShield",
                'red_artwork' => "ManaShield_red"
            ],
            [
                'id' => 20,
                'name' => 'Wandering Core',
                'top' => 5, 'right' => 5, 'bottom' => 1, 'left' => 5,
                'rarity' => CardRarity::COMMON,
                'description' => 'A sentient sphere of pure, unstable magic. It floats aimlessly across the ruined lands, discharging volatile elemental energy at anyone foolish enough to come close.',
                'category' => CardCategory::ARTIFACT,
                'blue_artwork' => "WanderingCore",
                'red_artwork' => "WanderingCore_red"
            ],
            [
                'id' => 21,
                'name' => 'Compass of Shadows',
                'top' => 1, 'right' => 7, 'bottom' => 7, 'left' => 4,
                'rarity' => CardRarity::RARE,
                'description' => 'A cursed artifact that never points north. Instead, it guides the bearer towards their deepest fears, revealing unseen pathways hidden within the dark void.',
                'category' => CardCategory::ARTIFACT,
                'blue_artwork' => "CompassOfShadows",
                'red_artwork' => "CompassOfShadows_red"
            ],
            [
                'id' => 22,
                'name' => 'Runic Sword',
                'top' => 7, 'right' => 2, 'bottom' => 5, 'left' => 6,
                'rarity' => CardRarity::RARE,
                'description' => 'Forged by master dwarven smiths, its glowing blade cuts through armor and magic alike. It empowers the wielder with the legendary strength of fallen heroes.',
                'category' => CardCategory::ARTIFACT,
                'blue_artwork' => "RunicSword",
                'red_artwork' => "RunicSword_red"
            ],
            [
                'id' => 23,
                'name' => 'Orb of Vision',
                'top' => 7, 'right' => 7, 'bottom' => 4, 'left' => 6,
                'rarity' => CardRarity::EPIC,
                'description' => 'A glowing sphere containing trapped starlight. It peers through the thickest illusions, revealing hidden enemies, lost treasures, and the deepest secrets of the forgotten past.',
                'category' => CardCategory::ARTIFACT,
                'blue_artwork' => "OrbOfVision",
                'red_artwork' => "OrbOfVision_red"
            ],
            [
                'id' => 24,
                'name' => 'Chalice of Energy',
                'top' => 10, 'right' => 3, 'bottom' => 10, 'left' => 3,
                'rarity' => CardRarity::LEGENDARY,
                'description' => 'A sacred vessel forged to hold the purest essence of life. Those who drink from it are granted unimaginable strength and boundless, eternal vitality.',
                'category' => CardCategory::ARTIFACT,
                'blue_artwork' => "ChaliceOfEnergy",
                'red_artwork' => "ChaliceOfEnergy_red"
            ],
        ];

        foreach ($cards as $card) {
            Card::updateOrCreate(['id' => $card['id']], $card);
        }
    }
}
