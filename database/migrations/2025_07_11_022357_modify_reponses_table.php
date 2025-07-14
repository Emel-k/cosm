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
        Schema::table('reponses', function (Blueprint $table) {
            // Renommer 'texte' en 'response_text' pour correspondre au MCD
            $table->renameColumn('texte', 'response_text');

            // Renommer 'valeur' en 'response_value' pour correspondre au MCD
            $table->renameColumn('valeur', 'response_value');

            // Ajouter la colonne score manquante du MCD
            $table->integer('response_score')->nullable()->after('response_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reponses', function (Blueprint $table) {
            // Remettre les noms originaux
            $table->renameColumn('response_text', 'texte');
            $table->renameColumn('response_value', 'valeur');

            // Supprimer la colonne score
            $table->dropColumn('response_score');
        });
    }
};
