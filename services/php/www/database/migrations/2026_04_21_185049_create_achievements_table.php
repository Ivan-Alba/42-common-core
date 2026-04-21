<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            // Unique code to identify the achievement in the code (e.g., 'FIRST_WIN')
            $table->string('code')->unique();
            // Category or tier of the achievement (e.g., bronze, silver, gold)
            $table->string('category')->default('bronze');
            // The number required to complete the achievement (n)
            $table->integer('goal')->default(1);
            // Experience points or reward points
            $table->integer('points')->default(0);
            // Card rewards (nullable, as not all achievements may have a card reward)
            $table->foreignId('card_reward_id')->nullable()->constrained('cards')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
