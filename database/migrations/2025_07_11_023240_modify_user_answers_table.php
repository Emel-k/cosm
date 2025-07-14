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
        Schema::table('user_answers', function (Blueprint $table) {
            // Rendre user_id nullable pour permettre les sessions anonymes
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Ajouter la référence vers user_sessions
            $table->unsignedBigInteger('session_id')->after('user_id');
            $table->foreign('session_id')->references('id')->on('user_sessions')->onDelete('cascade');

            // Renommer 'reponse_id' en 'response_id' pour correspondre au MCD
            $table->renameColumn('reponse_id', 'response_id');

            // Ajouter un timestamp pour savoir quand la réponse a été donnée
            $table->timestamp('answered_at')->useCurrent()->after('response_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            // Remettre user_id obligatoire
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // Supprimer la référence vers user_sessions
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');

            // Remettre le nom original
            $table->renameColumn('response_id', 'reponse_id');

            // Supprimer le timestamp
            $table->dropColumn('answered_at');
        });
    }
};
