<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('forecasts', function (Blueprint $table) {
            // Bezbedno brisanje samo ako kolone postoje
            if (Schema::hasColumn('forecasts', 'is_manual')) {
                $table->dropColumn('is_manual');
            }
            if (Schema::hasColumn('forecasts', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('forecasts', 'last_synced_at')) {
                $table->dropColumn('last_synced_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('forecasts', function (Blueprint $table) {
            if (!Schema::hasColumn('forecasts', 'is_manual')) {
                $table->boolean('is_manual')->default(false)->after('id');
            }
            if (!Schema::hasColumn('forecasts', 'source')) {
                $table->string('source')->nullable()->after('is_manual');
            }
            if (!Schema::hasColumn('forecasts', 'last_synced_at')) {
                $table->timestamp('last_synced_at')->nullable()->after('source');
            }
        });
    }
};
