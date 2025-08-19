<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weather', function (Blueprint $table) {
            // obriši staru kolonu "city" ako postoji
            if (Schema::hasColumn('weather', 'city')) {
                $table->dropColumn('city');
            }

            // dodaj novu kolonu "city_id"
            $table->unsignedBigInteger('city_id')->after('id');

            // foreign key ka cities tabeli
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            }
        );
    }

    public function down(): void
    {
        Schema::table('weather', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });
    }
};
