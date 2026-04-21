<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievement_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            // Current user progress (from 0 to achievement->goal)
            $table->integer('progress')->default(0);
            // If null, the achievement is still locked
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();

            // Ensure a user only has one entry per achievement
            $table->unique(['user_id', 'achievement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievement_user');
    }
};
