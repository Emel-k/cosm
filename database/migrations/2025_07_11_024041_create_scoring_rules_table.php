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
        Schema::create('scoring_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id'); // Référence vers la réponse
            $table->string('skin_type_indicator'); // Indicateur du type de peau (ex: 'grasse', 'seche', 'mixte')
            $table->integer('score_value'); // Valeur du score pour cette réponse
            $table->string('category')->nullable(); // Catégorie de scoring (ex: 'hydratation', 'sebum')
            $table->text('description')->nullable(); // Description de la règle
            $table->boolean('is_active')->default(true); // Activer/désactiver la règle
            $table->timestamps();

            // Clé étrangère
            $table->foreign('response_id')->references('id')->on('reponses')->onDelete('cascade');

            // Index pour optimiser les requêtes
            $table->index(['response_id', 'skin_type_indicator']);
            $table->index(['category', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_rules');
    }
};
