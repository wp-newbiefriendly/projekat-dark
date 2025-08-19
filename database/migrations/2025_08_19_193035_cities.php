<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Ova metoda se izvršava kada pokreneš `php artisan migrate`
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            // Primarni ključ (auto increment ID)
            $table->id();

            // Kolona za ime grada, tip string (VARCHAR u SQL-u)
            // ->unique() znači da isti naziv NE MOŽE da se ponovi
            $table->string('name')->unique();

            // Automatski dodaje created_at i updated_at kolone
            $table->timestamps();
        });
    }

    // Ova metoda se izvršava kada pokreneš `php artisan migrate:rollback`
    // Briše celu tabelu cities
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
