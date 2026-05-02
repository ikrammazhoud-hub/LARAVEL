<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : ajout des colonnes manquantes à la table machines.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (! Schema::hasColumn('machines', 'numero_serie')) {
                $table->string('numero_serie')->nullable()->after('localisation');
            }
            if (! Schema::hasColumn('machines', 'description')) {
                $table->text('description')->nullable()->after('numero_serie');
            }
        });
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumnIfExists('numero_serie');
            $table->dropColumnIfExists('description');
        });
    }
};
