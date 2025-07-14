<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('texte'); // Le contenu de la question
            $table->enum('type', ['peau', 'objectif'])->default('peau'); // Type de questionnaire
            $table->enum('niveau', ['débutant', 'intermédiaire', 'avancé'])->default('débutant'); // Niveau de complexité
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
