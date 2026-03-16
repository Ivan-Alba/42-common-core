<?php

use App\Enums\GameMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('player_1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('player_2_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('game_mode', array_column(GameMode::cases(), 'value'));
            
            $table->boolean('is_vs_ai')->default(false);
            $table->integer('p1_score')->default(0);
            $table->integer('p2_score')->default(0);
            
            $table->integer('p1_points_earned')->default(0);
            $table->integer('p2_points_earned')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
