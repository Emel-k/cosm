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
        Schema::create('diagnostic_thresholds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skin_characteristic_id'); // Référence vers le type de peau
            $table->string('criterion'); // Critère de diagnostic (ex: 'total_score', 'hydratation_score')
            $table->integer('min_threshold'); // Seuil minimum
            $table->integer('max_threshold'); // Seuil maximum
            $table->enum('test_level', ['general', 'approfondi']); // Niveau de test
            $table->integer('priority')->default(1); // Priorité si plusieurs seuils matchent
            $table->text('condition_description')->nullable(); // Description de la condition
            $table->boolean('is_active')->default(true); // Activer/désactiver
            $table->timestamps();

            // Clé étrangère
            $table->foreign('skin_characteristic_id')->references('id')->on('skin_characteristics')->onDelete('cascade');

            // Index pour optimiser les requêtes
            $table->index(['skin_characteristic_id', 'test_level']);
            $table->index(['criterion', 'is_active']);
            $table->index(['min_threshold', 'max_threshold']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_thresholds');
    }
};
