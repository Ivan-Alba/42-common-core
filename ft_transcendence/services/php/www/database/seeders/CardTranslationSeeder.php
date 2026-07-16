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
                Language::SPANISH->value => ['name' => 'Gran Inquisidor', 'description' => 'Un juez implacable de la verdad y la herejía. Busca el conocimiento prohibido, purgando el reino de magia oscura con puño de hierro y resolución.'],
                Language::ENGLISH->value => ['name' => 'Grand Inquisitor', 'description' => 'A ruthless judge of truth and heresy. He seeks out forbidden knowledge, purging the realm of dark magic with an iron fist and absolute resolve.'],
                Language::CATALAN->value => ['name' => 'Gran Inquisidor', 'description' => 'Un jutge implacable de la veritat i l\'heretgia. Busca el coneixement prohibit, purgant el regne de màgia fosca amb puny de ferro i resolució absoluta.']
            ],
            2 => [
                Language::SPANISH->value => ['name' => 'Sabio del Maná', 'description' => 'Un vagabundo pacífico que lleva la sabiduría de siglos. Restaura el equilibrio del mundo, ofreciendo guía tranquila y energía mágica inmensa a los campeones dignos.'],
                Language::ENGLISH->value => ['name' => 'Sage of Mana', 'description' => 'A peaceful wanderer carrying the wisdom of centuries. He restores balance to the world, offering quiet guidance and immense magical energy to worthy noble champions.'],
                Language::CATALAN->value => ['name' => 'Savi de Manà', 'description' => 'Un rodamón pacífic que porta la saviesa de segles. Restaura l\'equilibri del món, oferint guia tranquil·la i energia màgica immensa a tots els campions dignes.']
            ],
            3 => [
                Language::SPANISH->value => ['name' => 'Hechicera Dragón', 'description' => 'Canalizando la furia de los dracos, ella domina hechizos y escamas, mezclando magia arcana destructiva con la ira ardiente de las legendarias bestias aladas.'],
                Language::ENGLISH->value => ['name' => 'Dragon Sorceress', 'description' => 'Channeling the fury of ancient drakes, she commands both spell and scale, blending destructive arcane magic with the primal, burning wrath of legendary winged beasts.'],
                Language::CATALAN->value => ['name' => 'Fetillera Drac', 'description' => 'Canalitzant la fúria dels dracs, ella domina encanteris i escates, barrejant màgia arcana destructiva amb la ira ardent de les llegendàries bèsties alades del passat.']
            ],
            4 => [
                Language::SPANISH->value => ['name' => 'Alquimista de Maná', 'description' => 'Una mente brillante obsesionada con la esencia mágica. Destila la energía del mundo en elixires potentes, transformando líquidos simples en milagros de poder místico inimaginable.'],
                Language::ENGLISH->value => ['name' => 'Mana Alchemist', 'description' => 'A brilliant mind obsessed with magical essence. He distills the world\'s raw energy into potent elixirs, transforming simple liquids into miracles of unimaginable mystical power.'],
                Language::CATALAN->value => ['name' => 'Alquimista de Manà', 'description' => 'Una ment brillant obsessionada amb l\'essència màgica. Destil·la l\'energia del món en elixirs potents, transformant líquids simples en miracles de poder místic totalment inimaginable.']
            ],
            5 => [
                Language::SPANISH->value => ['name' => 'Hechicera de Runas', 'description' => 'Dominando los símbolos olvidados de poder, manipula el campo de batalla con sellos brillantes, desatando caos elemental sobre cualquiera que se cruce en su camino.'],
                Language::ENGLISH->value => ['name' => 'Rune Sorceress', 'description' => 'Mastering the forgotten symbols of power, she manipulates the battlefield with glowing seals, unleashing spectacular elemental chaos upon anyone foolish enough to cross her path.'],
                Language::CATALAN->value => ['name' => 'Fetillera de Runes', 'description' => 'Dominant símbols de poder oblidats, manipula el camp de batalla amb segells brillants, desfermant caos elemental sobre qualsevol que es creui en el seu camí.']
            ],
            6 => [
                Language::SPANISH->value => ['name' => 'Mago Rúnico', 'description' => 'Un maestro anciano de las artes arcanas. Lee el tejido del universo, lanzando hechizos complejos que alteran la realidad y cambian el rumbo de guerras.'],
                Language::ENGLISH->value => ['name' => 'Runic Wizard', 'description' => 'An elder master of the arcane arts. He reads the universe\'s fabric, casting complex spells that alter reality and turn the tide of great wars.'],
                Language::CATALAN->value => ['name' => 'Mag Rúnic', 'description' => 'Un mestre ancià de les arts arcanes. Llegeix el teixit de l\'univers, llançant encanteris complexos que alteren la realitat i canvien el rumb de guerres.']
            ],

            // --- ANIMALS CATEGORY ---
            7 => [
                Language::SPANISH->value => ['name' => 'Búho de Bronce', 'description' => 'Un antiguo guardián creado por mecánicos olvidados, vigila los cielos con precisión implacable, registrando cada secreto oculto en los rincones más oscuros del tiempo.'],
                Language::ENGLISH->value => ['name' => 'Bronze Owl', 'description' => 'An ancient guardian crafted by forgotten mechanics, it watches the skies with relentless precision, recording every hidden secret within the darkest corners of time.'],
                Language::CATALAN->value => ['name' => 'Mussol de Bronze', 'description' => 'Un antic guardià creat per mecànics oblidats, vigila els cels amb precisió implacable, registrant cada secret ocult en els racons més foscos del temps.']
            ],
            8 => [
                Language::SPANISH->value => ['name' => 'Cuervo de Hierro', 'description' => 'Forjado con armaduras desechadas, este carroñero mecánico espía desde las nubes grises, recolectando secretos brillantes y trayendo oscuros presagios al campo de batalla olvidado.'],
                Language::ENGLISH->value => ['name' => 'Iron Crow', 'description' => 'Forged from discarded armor, this mechanical scavenger spies from the gray clouds, collecting shiny secrets and bringing dark omens to the forgotten battlefield below.'],
                Language::CATALAN->value => ['name' => 'Corb de Ferro', 'description' => 'Forjat amb armadures rebutjades, aquest carronyer mecànic espia des dels núvols grisos, recol·lectant secrets brillants i portant obscurs presagis al camp de batalla oblidat.']
            ],
            9 => [
                Language::SPANISH->value => ['name' => 'Lobo Sombrío', 'description' => 'Una bestia fantasma formada por pesadillas oscuras. Caza implacablemente bajo la luz de la luna, desapareciendo en la niebla antes de que su presa reaccione.'],
                Language::ENGLISH->value => ['name' => 'Shadow Wolf', 'description' => 'A phantom beast formed from the darkest nightmares. It hunts relentlessly under the moonlight, vanishing into the mist before its terrified prey even senses danger.'],
                Language::CATALAN->value => ['name' => 'Llop Obscur', 'description' => 'Una bèstia fantasma formada per malsons foscos. Caça implacablement sota la llum de la lluna, desapareixent en la boira abans que la seva presa reaccioni.']
            ],
            10 => [
                Language::SPANISH->value => ['name' => 'Araña Mecánica', 'description' => 'Un terror intrincado construido con engranajes y precisión venenosa. Teje redes de acero invisibles, atrapando a los incautos antes de atacar con gracia mecánica letal.'],
                Language::ENGLISH->value => ['name' => 'Mechanical Spider', 'description' => 'An intricate terror built with clockwork gears and venomous precision. It weaves invisible steel webs, trapping the unwary before striking with lethal, calculated mechanical grace.'],
                Language::CATALAN->value => ['name' => 'Aranya Mecànica', 'description' => 'Un terror intricat construït amb engranatges i precisió verinosa. Teixeix xarxes d\'acer invisibles, atrapant als incauts abans d\'atacar amb gràcia mecànica totalment letal i calculada.']
            ],
            11 => [
                Language::SPANISH->value => ['name' => 'Serpiente de Cristal', 'description' => 'Acechando en cuevas brillantes, este hermoso pero letal depredador ataca con veneno perforador, convirtiendo la sangre de sus desafortunadas víctimas en cristal sólido al instante.'],
                Language::ENGLISH->value => ['name' => 'Crystal Snake', 'description' => 'Lurking silently in the glowing caves, this beautiful yet deadly predator strikes with piercing venom, turning the blood of its unfortunate victims into solid glass.'],
                Language::CATALAN->value => ['name' => 'Serp de Cristall', 'description' => 'Sotjant en coves brillants, aquest bell però letal depredador ataca amb verí perforador, convertint la sang de les seves víctimes en cristall sòlid a l\'instant.']
            ],
            12 => [
                Language::SPANISH->value => ['name' => 'Pantera de Humo', 'description' => 'Nacido de las cenizas de bosques quemados, este escurridizo felino ataca con velocidad cegadora. Deja solo un rastro de humo oscuro y una devastación silenciosa.'],
                Language::ENGLISH->value => ['name' => 'Smoke Panther', 'description' => 'Born from the ashes of burned forests, this elusive feline strikes with blinding speed. It leaves only a trail of dark smoke and silent devastation.'],
                Language::CATALAN->value => ['name' => 'Pantera de Fum', 'description' => 'Nascut de les cendres de boscos cremats, aquest escorredís felí ataca amb velocitat encegadora. Deixa només un rastre de fum fosc i una devastació silenciosa.']
            ],

            // --- BEASTS CATEGORY ---
            13 => [
                Language::SPANISH->value => ['name' => 'Gárgola de Piedra', 'description' => 'Una estatua animada ligada a antiguas catedrales. Protege terrenos sagrados de intrusos oscuros, descendiendo con peso aplastante y una resolución de granito totalmente inquebrantable.'],
                Language::ENGLISH->value => ['name' => 'Stone Gargoyle', 'description' => 'An animated statue bound to ancient cathedrals. It protects holy grounds from dark intruders, swooping down with crushing weight and unbreakable, cold, stony granite resolve.'],
                Language::CATALAN->value => ['name' => 'Gàrgola de Pedra', 'description' => 'Una estàtua animada lligada a antigues catedrals. Protegeix terrenys sagrats d\'intrusos foscos, descendint amb pes aclaparador i una resolució de granit que resulta totalment indestructible.']
            ],
            14 => [
                Language::SPANISH->value => ['name' => 'Coloso de Hierro', 'description' => 'Un imponente monumento de metal encantado. Este gigante indestructible aplasta ejércitos enteros bajo sus pesados pasos, sirviendo como el bastión definitivo de la defensa antigua.'],
                Language::ENGLISH->value => ['name' => 'Iron Colossus', 'description' => 'A towering monument of enchanted metal. This indestructible juggernaut crushes entire armies under its heavy steps, serving as the ultimate bastion of ancient earthly defense.'],
                Language::CATALAN->value => ['name' => 'Colós de Ferro', 'description' => 'Un imponent monument de metall encantat. Aquest gegant indestructible aixafa exèrcits sencers sota els seus pesats passos, servint com el bastió definitiu de l\'antiga defensa.']
            ],
            15 => [
                Language::SPANISH->value => ['name' => 'Forjador de Almas', 'description' => 'Un misterioso herrero del inframundo. Martilla los espíritus de los muertos en armas poderosas y eternas, uniendo el valor mortal al frío y despiadado acero.'],
                Language::ENGLISH->value => ['name' => 'Soul Forger', 'description' => 'A mysterious blacksmith dwelling in the underworld. He hammers the spirits of the dead into powerful, eternal weapons, binding mortal courage to cold, unforgiving steel.'],
                Language::CATALAN->value => ['name' => 'Forjador d\'Ànimes', 'description' => 'Un misteriós ferrer de l\'inframon. Martelleja els esperits dels morts en armes poderoses i eternes, unint el valor mortal al fred i totalment despietat acer.']
            ],
            16 => [
                Language::SPANISH->value => ['name' => 'Dragón Arcano', 'description' => 'Forjado en los fuegos primordiales de la magia, esta antigua criatura controla los elementos, trayendo devastación absoluta a quienes desafíen su inmenso poder.'],
                Language::ENGLISH->value => ['name' => 'Arcane Dragon', 'description' => 'Forged in the primordial fires of magic, this ancient creature controls the raw elements, bringing absolute devastation to those who challenge its boundless power.'],
                Language::CATALAN->value => ['name' => 'Drac Arcà', 'description' => 'Forjat en els focs primordials de la màgia, aquesta antiga criatura controla els elements, portant devastació absoluta a aquells que desafien el seu poder.']
            ],
            17 => [
                Language::SPANISH->value => ['name' => 'Centinela de Obsidiana', 'description' => 'Tallado en la piedra volcánica más oscura, este vigilante silencioso monta guardia eterna. Despierta solo cuando los reinos antiguos enfrentan la ruina y destrucción inminente.'],
                Language::ENGLISH->value => ['name' => 'Obsidian Sentinel', 'description' => 'Carved from the darkest volcanic stone, this silent watcher stands eternal guard. It awakens only when ancient realms face absolute ruin and inevitable fiery destruction.'],
                Language::CATALAN->value => ['name' => 'Sentinella d\'Obsidiana', 'description' => 'Tallat en la pedra volcànica més fosca, aquest vigilant silenciós fa guàrdia eterna. Desperta només quan els regnes antics enfronten la ruïna i destrucció imminent.']
            ],
            18 => [
                Language::SPANISH->value => ['name' => 'Leviatán Rúnico', 'description' => 'Un monstruoso titán de las profundidades, inscrito con runas antiguas. Su rugido masivo domina el océano, hundiendo flotas poderosas en el oscuro abismo de agua.'],
                Language::ENGLISH->value => ['name' => 'Runic Leviathan', 'description' => 'A monstrous titan of the deep, inscribed with glowing ancient runes. Its massive roar commands the ocean, sinking mighty fleets into the dark, watery abyss.'],
                Language::CATALAN->value => ['name' => 'Leviatan Rúnic', 'description' => 'Un monstruós tità de les profunditats, inscrit amb runes antigues. El seu rugit massiu domina l\'oceà, enfonsant flotes poderoses en el fosc abisme d\'aigua profunda.']
            ],

            // --- ARTIFACTS CATEGORY ---
            19 => [
                Language::SPANISH->value => ['name' => 'Escudo de Maná', 'description' => 'Tejida con hilos arcanos puros, esta barrera absorbe impactos mágicos devastadores, protegiendo al portador valiente al convertir la destrucción entrante en inofensivos estallidos de luz.'],
                Language::ENGLISH->value => ['name' => 'Mana Shield', 'description' => 'Woven from pure arcane threads, this barrier absorbs devastating magical impacts, protecting the brave bearer by turning incoming destruction into harmless bursts of radiant light.'],
                Language::CATALAN->value => ['name' => 'Escut de Manà', 'description' => 'Teixida amb fils arcans purs, aquesta barrera absorbeix impactes màgics devastadors, protegint al portador valent en convertir la destrucció entrant en inofensius esclats de llum.']
            ],
            20 => [
                Language::SPANISH->value => ['name' => 'Núcleo Errante', 'description' => 'Una esfera sintiente de magia pura e inestable. Flota sin rumbo por tierras en ruinas, descargando energía elemental volátil sobre cualquiera que se acerque demasiado.'],
                Language::ENGLISH->value => ['name' => 'Wandering Core', 'description' => 'A sentient sphere of pure, unstable magic. It floats aimlessly across the ruined lands, discharging volatile elemental energy at anyone foolish enough to come close.'],
                Language::CATALAN->value => ['name' => 'Nucli Errant', 'description' => 'Una esfera sentint de màgia pura i inestable. Flota sense rumb per terres en ruïnes, descarregant energia elemental volàtil sobre qualsevol que s\'apropi massa ràpidament.']
            ],
            21 => [
                Language::SPANISH->value => ['name' => 'Brújula de las Sombras', 'description' => 'Un artefacto maldito que nunca apunta al norte. En su lugar, guía al portador hacia sus miedos más profundos, revelando caminos ocultos en la oscuridad.'],
                Language::ENGLISH->value => ['name' => 'Compass of Shadows', 'description' => 'A cursed artifact that never points north. Instead, it guides the bearer towards their deepest fears, revealing unseen pathways hidden within the dark void.'],
                Language::CATALAN->value => ['name' => 'Brúixola de les Ombres', 'description' => 'Un artefacte maleït que mai apunta al nord. En comptes d\'això, guia el portador cap a les seves pors, revelant camins ocults en la foscor.']
            ],
            22 => [
                Language::SPANISH->value => ['name' => 'Espada Rúnica', 'description' => 'Forjada por maestros herreros, su hoja brillante corta armaduras y magia por igual. Otorga al portador la fuerza legendaria de todos los héroes antiguos caídos.'],
                Language::ENGLISH->value => ['name' => 'Runic Sword', 'description' => 'Forged by master dwarven smiths, its glowing blade cuts through armor and magic alike. It empowers the wielder with the legendary strength of fallen heroes.'],
                Language::CATALAN->value => ['name' => 'Espasa Rúnica', 'description' => 'Forjada per mestres ferrers, la seva fulla brillant talla armadures i màgia per igual. Atorga al portador la força llegendària de tots els herois caiguts.']
            ],
            23 => [
                Language::SPANISH->value => ['name' => 'Orbe de Visión', 'description' => 'Una esfera brillante que contiene luz estelar atrapada. Mira a través de las ilusiones, revelando enemigos ocultos, tesoros perdidos y secretos del pasado olvidado.'],
                Language::ENGLISH->value => ['name' => 'Orb of Vision', 'description' => 'A glowing sphere containing trapped starlight. It peers through the thickest illusions, revealing hidden enemies, lost treasures, and the deepest secrets of the forgotten past.'],
                Language::CATALAN->value => ['name' => 'Orbe de Visió', 'description' => 'Una esfera brillant que conté llum estel·lar atrapada. Mira a través de les il·lusions, revelant enemics ocults, tresors perduts i secrets del passat més oblidat.']
            ],
            24 => [
                Language::SPANISH->value => ['name' => 'Cáliz de Energía', 'description' => 'Un recipiente sagrado forjado para contener la esencia más pura. Quienes beben de él obtienen una fuerza inimaginable y una vitalidad eterna y sin límites.'],
                Language::ENGLISH->value => ['name' => 'Chalice of Energy', 'description' => 'A sacred vessel forged to hold the purest essence of life. Those who drink from it are granted unimaginable strength and boundless, eternal vitality.'],
                Language::CATALAN->value => ['name' => 'Calze d\'Energia', 'description' => 'Un recipient sagrat forjat per contenir l\'essència més pura. Aquells que beuen d\'ell obtenen una força inimaginable i una vitalidad eterna i sense límits.']
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
