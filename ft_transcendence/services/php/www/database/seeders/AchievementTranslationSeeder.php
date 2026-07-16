<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;
use App\Models\AchievementTranslation;
use App\Enums\Language;

class AchievementTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds for achievement translations.
     */
    public function run(): void
    {
        $translations = [
            'FIRST_WIN' => [
                Language::SPANISH->value => ['title' => 'Bautismo de Fuego', 'description' => 'Gana tu primera partida online.'],
                Language::ENGLISH->value => ['title' => 'Baptism of Fire', 'description' => 'Win your first online match.'],
                Language::CATALAN->value => ['title' => 'Baptisme de Foc', 'description' => 'Guanya la teva primera partida online.']
            ],
            'WIN_10_MATCHES' => [
                Language::SPANISH->value => ['title' => 'Luchador Incansable', 'description' => 'Logra la victoria en 10 enfrentamientos.'],
                Language::ENGLISH->value => ['title' => 'Relentless Fighter', 'description' => 'Achieve victory in 10 matches.'],
                Language::CATALAN->value => ['title' => 'Lluitador Incansable', 'description' => 'Aconsegueix la victòria en 10 enfrontaments.']
            ],
            'COLLECTOR_10' => [
                Language::SPANISH->value => ['title' => 'Pequeño Coleccionista', 'description' => 'Consigue 10 cartas diferentes para tu mazo.'],
                Language::ENGLISH->value => ['title' => 'Junior Collector', 'description' => 'Collect 10 different cards for your deck.'],
                Language::CATALAN->value => ['title' => 'Petit Col·leccionista', 'description' => 'Aconsegueix 10 cartes diferents per al teu mall.']
            ],
            'PERFECT_MATCH' => [
                Language::SPANISH->value => ['title' => 'Nexus Nine', 'description' => 'Gana una partida con una puntuación de 9.'],
                Language::ENGLISH->value => ['title' => 'Nexus Nine', 'description' => 'Win a match with a score of 9.'],
                Language::CATALAN->value => ['title' => 'Nexus Nine', 'description' => 'Guanya una partida amb una puntuació de 9.']
            ],
            'SOCIAL_BUTTERFLY' => [
                Language::SPANISH->value => ['title' => 'Camarada', 'description' => 'Haz tus primeros 5 amigos en la plataforma.'],
                Language::ENGLISH->value => ['title' => 'Comrade', 'description' => 'Make your first 5 friends on the platform.'],
                Language::CATALAN->value => ['title' => 'Camarada', 'description' => 'Fes els teus primers 5 amics a la plataforma.']
            ],
            'VETERAN_PLAYER' => [
                Language::SPANISH->value => ['title' => 'Leyenda del Tablero', 'description' => 'Juega un total de 100 partidas.'],
                Language::ENGLISH->value => ['title' => 'Legend of the Board', 'description' => 'Play a total of 100 matches.'],
                Language::CATALAN->value => ['title' => 'Llegenda del Tauler', 'description' => 'Juga un total de 100 partides.']
            ],
            'STREAK_3' => [
                Language::SPANISH->value => ['title' => 'Imparable', 'description' => 'Gana 3 partidas online consecutivas.'],
                Language::ENGLISH->value => ['title' => 'Unstoppable', 'description' => 'Win 3 online matches in a row.'],
                Language::CATALAN->value => ['title' => 'Imparable', 'description' => 'Guanya 3 partides online consecutives.']
            ],
            'LEVEL_10' => [
                Language::SPANISH->value => ['title' => 'Ascenso de Poder', 'description' => 'Alcanza el nivel de cuenta 10.'],
                Language::ENGLISH->value => ['title' => 'Power Surge', 'description' => 'Reach account level 10.'],
                Language::CATALAN->value => ['title' => 'Ascens de Poder', 'description' => 'Arriba al nivell de compte 10.']
            ],
            'DESTRUCTOR' => [
                Language::SPANISH->value => ['title' => 'Aniquilador', 'description' => 'Gira un total de 50 cartas de tus oponentes.'],
                Language::ENGLISH->value => ['title' => 'Annihilator', 'description' => 'Turn a total of 50 cards of your opponents.'],
                Language::CATALAN->value => ['title' => 'Aniquilador', 'description' => 'Gira un total de 50 cartes dels teus oponents.']
            ],
            'NIGHT_OWL' => [
                Language::SPANISH->value => ['title' => 'Ave Nocturna', 'description' => 'Gana una partida durante la madrugada.'],
                Language::ENGLISH->value => ['title' => 'Night Owl', 'description' => 'Win a match during the late-night hours.'],
                Language::CATALAN->value => ['title' => 'Mussol Nocturn', 'description' => 'Guanya una partida durant la matinada.']
            ],
        ];

        foreach ($translations as $code => $languages) {
            // Find achievement by its unique code
            $achievement = Achievement::where('code', $code)->first();

            if ($achievement) {
                foreach ($languages as $langValue => $data) {
                    AchievementTranslation::updateOrCreate(
                        [
                            'achievement_id' => $achievement->id,
                            'locale' => $langValue,
                        ],
                        [
                            'title' => $data['title'],
                            'description' => $data['description'],
                        ]
                    );
                }
            } else {
                $this->command->warn("Achievement with code {$code} not found. Skipping translations.");
            }
        }

        $this->command->info('Achievement translations seeded successfully!');
    }
}
