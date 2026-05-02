<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : ajout des colonnes manquantes à la table techniciens.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('techniciens', function (Blueprint $table) {
            if (! Schema::hasColumn('techniciens', 'matricule')) {
                $table->string('matricule')->nullable()->unique()->after('telephone');
            }
            if (! Schema::hasColumn('techniciens', 'disponible')) {
                $table->boolean('disponible')->default(true)->after('matricule');
            }
        });
    }

    public function down(): void
    {
        Schema::table('techniciens', function (Blueprint $table) {
            $table->dropColumnIfExists('matricule');
            $table->dropColumnIfExists('disponible');
        });
    }
};
