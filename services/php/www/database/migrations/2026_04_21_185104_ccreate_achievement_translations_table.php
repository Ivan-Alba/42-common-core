<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievement_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            // Language code (es, en, fr)
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description');

            // Prevent multiple translations for the same achievement and locale
            $table->unique(['achievement_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievement_translations');
    }
};
