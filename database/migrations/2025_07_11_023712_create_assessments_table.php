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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id'); // Référence vers la session
            $table->unsignedBigInteger('skin_characteristic_id'); // Type de peau déterminé
            $table->integer('total_score'); // Score total calculé
            $table->enum('confidence_level', ['faible', 'moyenne', 'élevée'])->default('moyenne'); // Niveau de confiance
            $table->json('detailed_scores')->nullable(); // Scores détaillés par catégorie
            $table->text('personalized_advice')->nullable(); // Conseils personnalisés
            $table->boolean('needs_advanced_test')->default(false); // Si questionnaire approfondi recommandé
            $table->timestamp('assessed_at')->useCurrent(); // Quand l'évaluation a été faite
            $table->timestamps();

            // Clés étrangères
            $table->foreign('session_id')->references('id')->on('user_sessions')->onDelete('cascade');
            $table->foreign('skin_characteristic_id')->references('id')->on('skin_characteristics')->onDelete('cascade');

            // Index pour optimiser les requêtes
            $table->index(['session_id', 'assessed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
