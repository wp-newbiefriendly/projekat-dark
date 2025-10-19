<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterForecastsAddSourceAndFlags extends Migration
{
    public function up()
    {
        Schema::table('forecasts', function (Blueprint $table) {
            $table->boolean('is_manual')->default(false)->after('id');
            $table->string('source')->nullable()->after('is_manual'); // 'manual' | 'api'
            $table->timestamp('last_synced_at')->nullable()->after('source');

            if (Schema::hasColumn('forecasts', 'probability')) {
                $table->renameColumn('probability', 'chance_of_rain');
            }
        });
    }

    public function down()
    {
        Schema::table('forecasts', function (Blueprint $table) {
            if (Schema::hasColumn('forecasts', 'chance_of_rain')) {
                $table->renameColumn('chance_of_rain', 'probability');
            }
            $table->dropColumn(['is_manual','source','last_synced_at']);
        });
    }
}
