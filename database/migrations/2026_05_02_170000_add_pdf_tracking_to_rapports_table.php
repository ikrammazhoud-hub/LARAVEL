<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            if (! Schema::hasColumn('rapports', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('recommandations');
            }

            if (! Schema::hasColumn('rapports', 'pdf_generated_at')) {
                $table->timestamp('pdf_generated_at')->nullable()->after('pdf_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropColumnIfExists('pdf_path');
            $table->dropColumnIfExists('pdf_generated_at');
        });
    }
};
