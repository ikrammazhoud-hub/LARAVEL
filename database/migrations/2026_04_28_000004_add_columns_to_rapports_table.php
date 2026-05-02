<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : ajout de machine_id et des nouveaux champs à la table rapports.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            if (! Schema::hasColumn('rapports', 'observations')) {
                $table->text('observations')->nullable()->after('contenu');
            }
            if (! Schema::hasColumn('rapports', 'pieces_changees')) {
                $table->text('pieces_changees')->nullable()->after('observations');
            }
            if (! Schema::hasColumn('rapports', 'recommandations')) {
                $table->text('recommandations')->nullable()->after('pieces_changees');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropColumnIfExists('observations');
            $table->dropColumnIfExists('pieces_changees');
            $table->dropColumnIfExists('recommandations');
        });
    }
};
