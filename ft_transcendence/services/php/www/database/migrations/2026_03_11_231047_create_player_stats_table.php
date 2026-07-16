<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * * This table stores player progress separately from the main users table
     * to optimize ranking queries and scalability.
     */
    public function up(): void
    {
        Schema::create('player_stats', function (Blueprint $table) {
            // Using user_id as the Primary Key for a strict 1:1 relationship
            $table->foreignId('user_id')->primary()->constrained()->cascadeOnDelete();
            
            $table->integer('level')->default(1);
            $table->bigInteger('experience')->default(0);
            $table->integer('achievement_points')->default(0);
            
            // Indexed for fast ranking and leaderboard retrieval
            $table->integer('ranked_points')->default(0)->index();
            
            // Nullable to handle players who haven't been ranked yet
            $table->integer('last_rank_pos')->nullable();
            
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('draws')->default(0);
            $table->integer('campaign')->default(1);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_stats');
    }
};
