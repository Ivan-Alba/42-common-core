<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CardTranslation;
use App\Enums\Language;

class CardTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds for card translations.
     * * This seeder uses the Language Enum to ensure consistency 
     * with the ISO 639-1 standard (es, en, ca).
     */
    public function run(): void
    {
        $translations = [
            // --- HUMAN CATEGORY ---
            1 => [
                Language::SPANISH->value => ['name' => 'Gran Inquisidor', 'description' => 'Un juez implacable de la verdad y la herejía. Busca el conocimiento prohibido.'],
                Language::ENGLISH->value => ['name' => 'Grand Inquisitor', 'description' => 'A relentless judge of truth and heresy. Seeking forbidden knowledge.'],
                Language::CATALAN->value => ['name' => 'Gran Inquisidor', 'description' => 'Un jutge implacable de la veritat i l\'heretgia. Cerca el coneixement prohibit.']
            ],
            2 => [
                Language::SPANISH->value => ['name' => 'Sabio del Maná', 'description' => 'Un vagabundo pacífico que lleva la sabiduría de siglos. Ofrece energía mágica inmensa.'],
                Language::ENGLISH->value => ['name' => 'Sage of Mana', 'description' => 'A peaceful wanderer carrying the wisdom of centuries. Offers immense magical energy.'],
                Language::CATALAN->value => ['name' => 'Savi del Manà', 'description' => 'Un vagabund pacífic que porta la saviesa de segles. Ofereix energia màgica immensa.']
            ],
            3 => [
                Language::SPANISH->value => ['name' => 'Hechicera Dragón', 'description' => 'Canalizando la furia de los dracos, domina hechizos y escamas con ira ardiente.'],
                Language::ENGLISH->value => ['name' => 'Dragon Sorceress', 'description' => 'Channeling the fury of drakes, she masters spells and scales with burning rage.'],
                Language::CATALAN->value => ['name' => 'Hechicera Drac', 'description' => 'Canalitzant la fúria dels dracs, domina encanteris i escates amb ira ardent.']
            ],
            4 => [
                Language::SPANISH->value => ['name' => 'Alquimista de Maná', 'description' => 'Destila la energía del mundo en elixires potentes, transformando líquidos en milagros.'],
                Language::ENGLISH->value => ['name' => 'Mana Alchemist', 'description' => 'Distills the world\'s energy into potent elixirs, transforming liquids into miracles.'],
                Language::CATALAN->value => ['name' => 'Alquimista de Manà', 'description' => 'Destil·la l\'energia del món en elixirs potents, transformant líquids en miracles.']
            ],
            5 => [
                Language::SPANISH->value => ['name' => 'Hechicera de Runas', 'description' => 'Manipula el campo de batalla con sellos brillantes, desatando el caos elemental.'],
                Language::ENGLISH->value => ['name' => 'Rune Sorceress', 'description' => 'Manipulates the battlefield with glowing sigils, unleashing elemental chaos.'],
                Language::CATALAN->value => ['name' => 'Hechicera de Runes', 'description' => 'Manipula el camp de batalla amb segells brillants, deslligant el caos elemental.']
            ],
            6 => [
                Language::SPANISH->value => ['name' => 'Mago Rúnico', 'description' => 'Maestro anciano de las artes arcanas. Altera la realidad y cambia el rumbo de las guerras.'],
                Language::ENGLISH->value => ['name' => 'Runic Wizard', 'description' => 'Ancient master of the arcane arts. Alters reality and turns the tide of wars.'],
                Language::CATALAN->value => ['name' => 'Mag Rúnic', 'description' => 'Mestre ancià de les arts arcanes. Altera la realitat i canvia el rumb de les guerres.']
            ],

            // --- ANIMALS CATEGORY ---
            7 => [
                Language::SPANISH->value => ['name' => 'Búho de Bronce', 'description' => 'Antiguo guardián mecánico que vigila los cielos con precisión implacable.'],
                Language::ENGLISH->value => ['name' => 'Bronze Owl', 'description' => 'Ancient mechanical guardian watching the skies with relentless precision.'],
                Language::CATALAN->value => ['name' => 'Mussol de Bronze', 'description' => 'Antic guardià mecànic que vigila els cels amb precisió implacable.']
            ],
            8 => [
                Language::SPANISH->value => ['name' => 'Cuervo de Hierro', 'description' => 'Carroñero mecánico que recolecta secretos brillantes y trae oscuros presagios.'],
                Language::ENGLISH->value => ['name' => 'Iron Crow', 'description' => 'Mechanical scavenger collecting glowing secrets and bringing dark omens.'],
                Language::CATALAN->value => ['name' => 'Corb de Ferro', 'description' => 'Carronyer mecànic que recull secrets brillants i porta presagis fosc.']
            ],
            9 => [
                Language::SPANISH->value => ['name' => 'Lobo Sombrío', 'description' => 'Bestia fantasma de pesadillas oscuras que caza bajo la luz de la luna.'],
                Language::ENGLISH->value => ['name' => 'Shadow Wolf', 'description' => 'Phantom beast of dark nightmares hunting under the moonlight.'],
                Language::CATALAN->value => ['name' => 'Llop Ombrívol', 'description' => 'Bèstia fantasma de malsons foscos que caça sota la llum de la lluna.']
            ],
            10 => [
                Language::SPANISH->value => ['name' => 'Araña Mecánica', 'description' => 'Terror de engranajes y precisión venenosa que teje redes de acero invisibles.'],
                Language::ENGLISH->value => ['name' => 'Mechanical Spider', 'description' => 'Terror of gears and poisonous precision weaving invisible steel webs.'],
                Language::CATALAN->value => ['name' => 'Aranya Mecànica', 'description' => 'Terror d\'engranatges i precisió venenosa que teixeix xarxes d\'acer invisibles.']
            ],
            11 => [
                Language::SPANISH->value => ['name' => 'Serpiente de Cristal', 'description' => 'Depredador de cuevas brillantes cuyo veneno convierte la sangre en cristal sólido.'],
                Language::ENGLISH->value => ['name' => 'Crystal Snake', 'description' => 'Predator of glowing caves whose venom turns blood into solid crystal.'],
                Language::CATALAN->value => ['name' => 'Serp de Cristall', 'description' => 'Depredador de coves brillants el verí de les quals converteix la sang en cristall sòlid.']
            ],
            12 => [
                Language::SPANISH->value => ['name' => 'Pantera de Humo', 'description' => 'Felino escurridizo nacido de cenizas que deja solo un rastro de humo y devastación.'],
                Language::ENGLISH->value => ['name' => 'Smoke Panther', 'description' => 'Elusive feline born from ashes leaving only a trail of smoke and devastation.'],
                Language::CATALAN->value => ['name' => 'Pantera de Fum', 'description' => 'Felí esmunyedís nascut de cendres que deixa només un rastre de fum i devastació.']
            ],

            // --- BEASTS CATEGORY ---
            13 => [
                Language::SPANISH->value => ['name' => 'Gárgola de Piedra', 'description' => 'Estatua animada de resolución de granito inquebrantable que protege terrenos sagrados.'],
                Language::ENGLISH->value => ['name' => 'Stone Gargoyle', 'description' => 'Animated statue of unshakable granite resolve protecting sacred grounds.'],
                Language::CATALAN->value => ['name' => 'Gàrgola de Pedra', 'description' => 'Estàtua animada de resolució de granit inquebrantable que protegeix terrenys sagrats.']
            ],
            14 => [
                Language::SPANISH->value => ['name' => 'Coloso de Hierro', 'description' => 'Gigante indestructible de metal encantado, bastión definitivo de la defensa antigua.'],
                Language::ENGLISH->value => ['name' => 'Iron Colossus', 'description' => 'Indestructible giant of enchanted metal, ultimate bastion of ancient defense.'],
                Language::CATALAN->value => ['name' => 'Colós de Ferro', 'description' => 'Gegant indestructible de metall encantat, bastió definitiu de la defensa antiga.']
            ],
            15 => [
                Language::SPANISH->value => ['name' => 'Forjador de Almas', 'description' => 'Herrero del inframundo que martilla espíritus en armas de frío acero.'],
                Language::ENGLISH->value => ['name' => 'Soul Forger', 'description' => 'Underworld blacksmith hammering spirits into weapons of cold steel.'],
                Language::CATALAN->value => ['name' => 'Forjador d\'Ànimes', 'description' => 'Ferrer de l\'inframón que martelleja esperits en armes de fred acer.']
            ],
            16 => [
                Language::SPANISH->value => ['name' => 'Dragón Arcano', 'description' => 'Criatura de fuegos primordiales que controla los elementos con devastación absoluta.'],
                Language::ENGLISH->value => ['name' => 'Arcane Dragon', 'description' => 'Creature of primordial fires controlling the elements with absolute devastation.'],
                Language::CATALAN->value => ['name' => 'Drac Arcà', 'description' => 'Criatura de focs primordials que controla els elements amb devastació absoluta.']
            ],
            17 => [
                Language::SPANISH->value => ['name' => 'Centinela de Obsidiana', 'description' => 'Vigilante de piedra volcánica que despierta ante la ruina de reinos antiguos.'],
                Language::ENGLISH->value => ['name' => 'Obsidian Sentinel', 'description' => 'Watcher of volcanic stone awakening to the ruin of ancient kingdoms.'],
                Language::CATALAN->value => ['name' => 'Centinella d\'Obsidiana', 'description' => 'Vigilant de pedra volcànica que es desperta davant la ruïna de regnes antics.']
            ],
            18 => [
                Language::SPANISH->value => ['name' => 'Leviatán Rúnico', 'description' => 'Titán de las profundidades inscrito con runas cuyo rugido domina el océano.'],
                Language::ENGLISH->value => ['name' => 'Runic Leviathan', 'description' => 'Titan of the deep inscribed with runes whose roar dominates the ocean.'],
                Language::CATALAN->value => ['name' => 'Leviatan Rúnic', 'description' => 'Tità de les profunditats inscrit amb runes el rugit de les quals domina l\'oceà.']
            ],

            // --- ARTIFACTS CATEGORY ---
            19 => [
                Language::SPANISH->value => ['name' => 'Escudo de Maná', 'description' => 'Barrera de hilos arcanos puros que absorbe impactos mágicos devastadores.'],
                Language::ENGLISH->value => ['name' => 'Mana Shield', 'description' => 'Barrier of pure arcane threads absorbing devastating magical impacts.'],
                Language::CATALAN->value => ['name' => 'Escut de Manà', 'description' => 'Barrera de fils arcans purs que absorbeix impactes màgics devastadors.']
            ],
            20 => [
                Language::SPANISH->value => ['name' => 'Núcleo Errante', 'description' => 'Esfera sintiente de magia inestable que descarga energía volátil.'],
                Language::ENGLISH->value => ['name' => 'Wandering Core', 'description' => 'Sentient sphere of unstable magic discharging volatile energy.'],
                Language::CATALAN->value => ['name' => 'Nucli Errant', 'description' => 'Esfera conscient de màgia inestable que descarrega energia volàtil.']
            ],
            21 => [
                Language::SPANISH->value => ['name' => 'Brújula de Sombras', 'description' => 'Artefacto maldito que guía al portador hacia sus miedos más profundos.'],
                Language::ENGLISH->value => ['name' => 'Compass of Shadows', 'description' => 'Cursed artifact guiding the bearer toward their deepest fears.'],
                Language::CATALAN->value => ['name' => 'Brúixola d\'Ombres', 'description' => 'Artefacte maleït que guia el portador cap a les seves pors més profundes.']
            ],
            22 => [
                Language::SPANISH->value => ['name' => 'Espada Rúnica', 'description' => 'Hoja brillante que corta armaduras y otorga fuerza de héroes caídos.'],
                Language::ENGLISH->value => ['name' => 'Runic Sword', 'description' => 'Glowing blade cutting through armor and granting strength of fallen heroes.'],
                Language::CATALAN->value => ['name' => 'Espasa Rúnica', 'description' => 'Fulla brillant que talla armadures i atorga força d\'herois caiguts.']
            ],
            23 => [
                Language::SPANISH->value => ['name' => 'Orbe de Visión', 'description' => 'Esfera de luz estelar que revela enemigos ocultos y secretos del pasado.'],
                Language::ENGLISH->value => ['name' => 'Orb of Vision', 'description' => 'Sphere of starlight revealing hidden enemies and secrets of the past.'],
                Language::CATALAN->value => ['name' => 'Orbe de Visió', 'description' => 'Esfera de llum estel·lar que revela enemics ocults i secrets del passat.']
            ],
            24 => [
                Language::SPANISH->value => ['name' => 'Cáliz de Energía', 'description' => 'Recipiente sagrado que otorga fuerza inimaginable y vitalidad eterna.'],
                Language::ENGLISH->value => ['name' => 'Chalice of Energy', 'description' => 'Sacred vessel granting unimaginable strength and eternal vitality.'],
                Language::CATALAN->value => ['name' => 'Càlzer d\'Energia', 'description' => 'Recipient sagrat que atorga força inimaginable i vitalitat eterna.']
            ],
        ];

        foreach ($translations as $cardId => $languages) {
            foreach ($languages as $langValue => $data) {
                CardTranslation::updateOrCreate(
                    [
                        'card_id' => $cardId,
                        'language' => $langValue,
                    ],
                    [
                        'name' => $data['name'],
                        'description' => $data['description'],
                    ]
                );
            }
        }
    }
}
