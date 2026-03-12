<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Language;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_translations', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('card_id')->constrained()->onDelete('cascade');

            $table->enum('language', [
                Language::SPANISH->value, 
                Language::ENGLISH->value, 
                Language::CATALAN->value
            ])->default(Language::ENGLISH->value);

            $table->string('name');
            $table->text('description');
            
            $table->timestamps();

            $table->unique(['card_id', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_translations');
    }
};
