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
        Schema::create('skin_characteristics', function (Blueprint $table) {
            $table->id();
            $table->string('skin_type'); // Type de peau (ex: "Peau grasse", "Peau sèche", etc.)
            $table->text('description'); // Description détaillée du type de peau
            $table->json('characteristics'); // Caractéristiques sous forme JSON
            $table->text('advice'); // Conseils pour ce type de peau
            $table->text('products_recommendation')->nullable(); // Recommandations de produits
            $table->integer('min_score'); // Score minimum pour ce type de peau
            $table->integer('max_score'); // Score maximum pour ce type de peau
            $table->boolean('is_active')->default(true); // Activer/désactiver
            $table->timestamps();

            // Index pour optimiser les requêtes de scoring
            $table->index(['min_score', 'max_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skin_characteristics');
    }
};
