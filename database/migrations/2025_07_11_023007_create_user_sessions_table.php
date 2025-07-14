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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_token')->unique(); // Token unique pour identifier la session
            $table->unsignedBigInteger('user_id')->nullable(); // Utilisateur connecté (optionnel)
            $table->enum('session_type', ['general', 'approfondi'])->default('general'); // Type de questionnaire
            $table->enum('status', ['en_cours', 'termine', 'abandonne'])->default('en_cours'); // Statut de la session
            $table->timestamp('started_at')->useCurrent(); // Début de la session
            $table->timestamp('completed_at')->nullable(); // Fin de la session
            $table->json('session_data')->nullable(); // Données additionnelles (optionnel)
            $table->timestamps();

            // Clé étrangère optionnelle vers users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index pour optimiser les requêtes
            $table->index(['session_token', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
