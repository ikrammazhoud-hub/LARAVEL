<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : création de la table notifications.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');          // tache_assignee | statut_change | alerte_machine
            $table->string('titre');
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->json('data')->nullable(); // métadonnées optionnelles
            $table->timestamps();

            $table->index(['user_id', 'lu']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
