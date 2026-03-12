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
                'description' => 'Un juez implacable de la verdad y la herejía. Busca el conocimiento prohibido.'
            ],
            [
                'id' => 2,
                'name' => 'Sage of Mana',
                'top' => 2, 'right' => 6, 'bottom' => 2, 'left' => 6,
                'rarity' => CardRarity::COMMON,
                'description' => 'Un vagabundo pacífico que lleva la sabiduría de siglos. Ofrece energía mágica inmensa.'
            ],
            [
                'id' => 3,
                'name' => 'Dragon Sorceress',
                'top' => 7, 'right' => 4, 'bottom' => 2, 'left' => 6,
                'rarity' => CardRarity::RARE,
                'description' => 'Canalizando la furia de los dracos, domina hechizos y escamas con ira ardiente.'
            ],
            [
                'id' => 4,
                'name' => 'Mana Alchemist',
                'top' => 3, 'right' => 5, 'bottom' => 7, 'left' => 4,
                'rarity' => CardRarity::RARE,
                'description' => 'Destila la energía del mundo en elixires potentes, transformando líquidos en milagros.'
            ],
            [
                'id' => 5,
                'name' => 'Rune Sorceress',
                'top' => 8, 'right' => 2, 'bottom' => 7, 'left' => 6,
                'rarity' => CardRarity::EPIC,
                'description' => 'Manipula el campo de batalla con sellos brillantes, desatando el caos elemental.'
            ],
            [
                'id' => 6,
                'name' => 'Runic Wizard',
                'top' => 10, 'right' => 4, 'bottom' => 8, 'left' => 5, // 'A' = 10
                'rarity' => CardRarity::GOLDEN,
                'description' => 'Maestro anciano de las artes arcanas. Altera la realidad y cambia el rumbo de las guerras.'
            ],

            // --- CATEGORY: ANIMALS ---
            [
                'id' => 7,
                'name' => 'Bronze Owl',
                'top' => 6, 'right' => 2, 'bottom' => 5, 'left' => 3,
                'rarity' => CardRarity::COMMON,
                'description' => 'Antiguo guardián mecánico que vigila los cielos con precisión implacable.'
            ],
            [
                'id' => 8,
                'name' => 'Iron Crow',
                'top' => 4, 'right' => 5, 'bottom' => 3, 'left' => 5,
                'rarity' => CardRarity::COMMON,
                'description' => 'Carroñero mecánico que recolecta secretos brillantes y trae oscuros presagios.'
            ],
            [
                'id' => 9,
                'name' => 'Shadow Wolf',
                'top' => 3, 'right' => 4, 'bottom' => 6, 'left' => 2,
                'rarity' => CardRarity::COMMON,
                'description' => 'Bestia fantasma de pesadillas oscuras que caza bajo la luz de la luna.'
            ],
            [
                'id' => 10,
                'name' => 'Mechanical Spider',
                'top' => 5, 'right' => 7, 'bottom' => 4, 'left' => 3,
                'rarity' => CardRarity::RARE,
                'description' => 'Terror de engranajes y precisión venenosa que teje redes de acero invisibles.'
            ],
            [
                'id' => 11,
                'name' => 'Crystal Snake',
                'top' => 2, 'right' => 8, 'bottom' => 5, 'left' => 8,
                'rarity' => CardRarity::EPIC,
                'description' => 'Depredador de cuevas brillantes cuyo veneno convierte la sangre en cristal sólido.'
            ],
            [
                'id' => 12,
                'name' => 'Smoke Panther',
                'top' => 6, 'right' => 9, 'bottom' => 3, 'left' => 10, // 'A' = 10
                'rarity' => CardRarity::GOLDEN,
                'description' => 'Felino escurridizo nacido de cenizas que deja solo un rastro de humo y devastación.'
            ],

            // --- CATEGORY: BEASTS ---
            [
                'id' => 13,
                'name' => 'Stone Gargoyle',
                'top' => 2, 'right' => 7, 'bottom' => 2, 'left' => 4,
                'rarity' => CardRarity::COMMON,
                'description' => 'Estatua animada de resolución de granito inquebrantable que protege terrenos sagrados.'
            ],
            [
                'id' => 14,
                'name' => 'Iron Colossus',
                'top' => 6, 'right' => 6, 'bottom' => 6, 'left' => 2,
                'rarity' => CardRarity::RARE,
                'description' => 'Gigante indestructible de metal encantado, bastión definitivo de la defensa antigua.'
            ],
            [
                'id' => 15,
                'name' => 'Soul Forger',
                'top' => 4, 'right' => 3, 'bottom' => 8, 'left' => 5,
                'rarity' => CardRarity::RARE,
                'description' => 'Herrero del inframundo que martilla espíritus en armas de frío acero.'
            ],
            [
                'id' => 16,
                'name' => 'Arcane Dragon',
                'top' => 8, 'right' => 6, 'bottom' => 2, 'left' => 8,
                'rarity' => CardRarity::EPIC,
                'description' => 'Criatura de fuegos primordiales que controla los elementos con devastación absoluta.'
            ],
            [
                'id' => 17,
                'name' => 'Obsidians Sentinel',
                'top' => 4, 'right' => 8, 'bottom' => 8, 'left' => 4,
                'rarity' => CardRarity::EPIC,
                'description' => 'Vigilante de piedra volcánica que despierta ante la ruina de reinos antiguos.'
            ],
            [
                'id' => 18,
                'name' => 'Runic Leviathan',
                'top' => 8, 'right' => 10, 'bottom' => 5, 'left' => 7, // 'A' = 10
                'rarity' => CardRarity::GOLDEN,
                'description' => 'Titán de las profundidades inscrito con runas cuyo rugido domina el océano.'
            ],

            // --- CATEGORY: ARTIFACTS ---
            [
                'id' => 19,
                'name' => 'Mana Shield',
                'top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4,
                'rarity' => CardRarity::COMMON,
                'description' => 'Barrera de hilos arcanos puros que absorbe impactos mágicos devastadores.'
            ],
            [
                'id' => 20,
                'name' => 'Wandering Core',
                'top' => 5, 'right' => 5, 'bottom' => 1, 'left' => 5,
                'rarity' => CardRarity::COMMON,
                'description' => 'Esfera sintiente de magia inestable que descarga energía volátil.'
            ],
            [
                'id' => 21,
                'name' => 'Compass of Shadows',
                'top' => 1, 'right' => 7, 'bottom' => 7, 'left' => 4,
                'rarity' => CardRarity::RARE,
                'description' => 'Artefacto maldito que guía al portador hacia sus miedos más profundos.'
            ],
            [
                'id' => 22,
                'name' => 'Runic Sword',
                'top' => 7, 'right' => 2, 'bottom' => 5, 'left' => 6,
                'rarity' => CardRarity::RARE,
                'description' => 'Hoja brillante que corta armaduras y otorga fuerza de héroes caídos.'
            ],
            [
                'id' => 23,
                'name' => 'Orb of Vision',
                'top' => 7, 'right' => 7, 'bottom' => 4, 'left' => 6,
                'rarity' => CardRarity::EPIC,
                'description' => 'Esfera de luz estelar que revela enemigos ocultos y secretos del pasado.'
            ],
            [
                'id' => 24,
                'name' => 'Chalice of Energy',
                'top' => 10, 'right' => 3, 'bottom' => 10, 'left' => 3, // 'A' = 10
                'rarity' => CardRarity::GOLDEN,
                'description' => 'Recipiente sagrado que otorga fuerza inimaginable y vitalidad eterna.'
            ],
        ];

        foreach ($cards as $card) {
            // Update the record if it exists based on ID, otherwise create it
            Card::updateOrCreate(['id' => $card['id']], $card);
        }
    }
}
