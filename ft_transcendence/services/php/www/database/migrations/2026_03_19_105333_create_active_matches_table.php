<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\MatchStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('active_matches', function (Blueprint $table) {
            $table->id();
            
            // Public identifier for Unity/Frontend communication
            $table->uuid('match_uuid')->unique(); 
            
            // Core players
            $table->foreignId('player_1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('player_2_id')->constrained('users')->onDelete('cascade');
            
            // The chosen game mode (Enum)
            $table->string('game_mode'); 

            // Game State Machine: 'selecting', 'playing', 'finished'
            $table->string('status')->default(MatchStatus::PENDING->value);
            
            // Tracks current turn and first player (decided at match creation)
            $table->foreignId('first_player_id')->constrained('users');
            $table->foreignId('current_turn_player_id')->nullable()->constrained('users');

            // JSON storage for the 3x3 board [0-8]
            $table->json('board_state')->nullable();

            // JSON storage for player hands: { "p1": ["id1", "id2"], "p2": [...] }
            $table->json('hands_state')->nullable();

            // Ready flags for the selection phase
            $table->boolean('p1_ready')->default(false);
            $table->boolean('p2_ready')->default(false);

            // --- DISCONNECTION & HEARTBEAT SYSTEM ---

            // Permanent flags: Once true, the player cannot reconnect
            $table->boolean('p1_disconnected')->default(false);
            $table->boolean('p2_disconnected')->default(false);

            // Timestamps to validate if a disconnection report is legitimate
            // We use decimal(15,3) for millisecond precision matching your timeout system
            $table->decimal('last_ping_p1', 15, 3)->nullable();
            $table->decimal('last_ping_p2', 15, 3)->nullable();

            // Precise timestamp for the next timeout
            $table->decimal('next_timeout_at', 15, 3)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_matches');
    }
};
