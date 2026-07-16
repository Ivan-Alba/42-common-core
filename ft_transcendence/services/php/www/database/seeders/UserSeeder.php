<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $avatarFolder = 'media/avatars';
        Storage::disk('public')->makeDirectory($avatarFolder);

        // 1. Master Bot - Nivel máximo y estadísticas perfectas
        $bot = $this->createSystemUser(
            'Master Bot',
            'master@example.com',
            'botAvatar.png',
            'I am the ultimate challenge. Can you beat the machine?',
            '@Master123',
            true
        );
        $bot->stats()->update([
            'level' => 50,
            'experience' => 0,
            'ranked_points' => 5000,
            'wins' => 1000,
            'losses' => 0,
            'draws' => 0,
            'last_rank_pos' => 1
        ]);

        // 2. Definición de Jugadores con sus stats específicas
        $players = [
            [
                'name' => 'Evaluator', 
                'bio' => 'Analyzing every move. I know your deck better than you do.',
                'stats' => ['level' => 25, 'xp' => 8950, 'rp' => 2800, 'w' => 150, 'l' => 45, 'd' => 10, 'last_pos' => 5]
            ],
            [
                'name' => 'Ivan', 
                'bio' => 'Aggressive playstyle. Always looking for the lethal blow.',
                'stats' => ['level' => 20, 'xp' => 7500, 'rp' => 2450, 'w' => 120, 'l' => 33, 'd' => 5, 'last_pos' => 3]
            ],
            [
                'name' => 'Miriam', 
                'bio' => 'Strategy is my middle name. I control the board, I control the game.',
                'stats' => ['level' => 18, 'xp' => 200, 'rp' => 2100, 'w' => 95, 'l' => 40, 'd' => 15, 'last_pos' => 2]
            ],
            [
                'name' => 'David', 
                'bio' => 'The silent climber. Slowly building the perfect hand.',
                'stats' => ['level' => 12, 'xp' => 50, 'rp' => 1600, 'w' => 60, 'l' => 55, 'd' => 8, 'last_pos' => 8]
            ],
            [
                'name' => 'Kevin', 
                'bio' => 'Chaos is my ladder. Expect the unexpected.',
                'stats' => ['level' => 5, 'xp' => 120, 'rp' => 1100, 'w' => 25, 'l' => 30, 'd' => 2, 'last_pos' => 2]
            ],
        ];

        foreach ($players as $p) {
            $name = $p['name'];
            $email = strtolower($name) . '@' . strtolower($name) . '.com';
            $password = '@' . $name . '123';
            $avatarName = strtolower($name) . 'Avatar.png';

            $user = $this->createSystemUser($name, $email, $avatarName, $p['bio'], $password);
            
            $user->stats()->update([
                'level' => $p['stats']['level'],
                'experience' => $p['stats']['xp'],
                'ranked_points' => $p['stats']['rp'],
                'wins' => $p['stats']['w'],
                'losses' => $p['stats']['l'],
                'draws' => $p['stats']['d'],
                'last_rank_pos' => $p['stats']['last_pos'],
            ]);
        }
    }

    private function createSystemUser(string $name, string $email, string $avatarName, string $bio, string $password, bool $isBot = false): User
    {
        $avatarFolder = 'media/avatars';
        $relativePath = $avatarFolder . '/' . $avatarName;
        $sourcePath = database_path('seeders/assets/' . $avatarName);

        if (File::exists($sourcePath)) {
            Storage::disk('public')->put($relativePath, File::get($sourcePath));
        }

        return User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'avatar' => $relativePath,
            'bio' => $bio,
            'is_bot' => $isBot,
        ]);
    }
}
