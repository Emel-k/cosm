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
        Schema::table('questions', function (Blueprint $table) {
            // Modifier la colonne niveau pour correspondre à votre logique métier
            $table->enum('niveau', ['general', 'approfondi'])->default('general')->change();

            // Renommer 'texte' en 'question_text' pour correspondre au MCD
            $table->renameColumn('texte', 'question_text');

            // Ajouter les colonnes manquantes du MCD
            $table->string('category')->after('question_text'); // Catégorie de la question
            $table->integer('question_order')->after('category'); // Ordre d'affichage
            $table->string('question_type')->after('question_order'); // Type de question
            $table->boolean('is_active')->default(true)->after('question_type'); // Activer/désactiver
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Remettre la colonne niveau avec les valeurs originales
            $table->enum('niveau', ['general', 'approfondi'])->default('general');

            // Remettre le nom original
            $table->renameColumn('question_text', 'texte');

            // Supprimer les colonnes ajoutées
            $table->dropColumn(['category', 'question_order', 'question_type', 'is_active']);
        });
    }
};
