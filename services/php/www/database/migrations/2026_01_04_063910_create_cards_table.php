<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CardCategory;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', array_column(CardCategory::cases(), 'value'))
                ->default(CardCategory::HUMAN->value);
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->integer('top');
            $table->integer('bottom');
            $table->integer('left');
            $table->integer('right');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
