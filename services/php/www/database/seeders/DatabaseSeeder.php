<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CardSeeder::class,
            CardTranslationSeeder::class,
            AchievementSeeder::class,
            AchievementTranslationSeeder::class,
        ]);

        // Master Bot user creation
        $avatarName = 'botAvatar.png';
        $relativeDestPath = 'media/avatars/' . $avatarName;

        Storage::disk('public')->makeDirectory('media/avatars');

        $sourcePath = database_path('seeders/assets/' . $avatarName);

        if (File::exists($sourcePath)) {
            Storage::disk('public')->put($relativeDestPath, File::get($sourcePath));
        }

        User::factory()->create([
            'name' => 'Master Bot',
            'email' => 'master@example.com',
            'avatar' => $relativeDestPath,
            'is_bot' => true,
        ]);
    }
}
