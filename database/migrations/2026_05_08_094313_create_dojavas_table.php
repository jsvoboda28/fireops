<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dojavas', function (Blueprint $table) {
            $table->id();
            
            // Identifikacija
            $table->string('broj_dojave', 20)->unique();
            
            // Vremena
            $table->timestamp('vrijeme_zaprimanja')->useCurrent();
            $table->timestamp('vrijeme_zatvaranja')->nullable();
            
            // Kanal i operater
            $table->string('kanal_dojave', 50)->default('112');
            $table->foreignId('operater_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Prijavitelj
            $table->string('prijavitelj_ime', 200)->nullable();
            $table->string('prijavitelj_telefon', 50)->nullable();
            $table->boolean('prijavitelj_anoniman')->default(false);
            
            // Lokacija
            $table->string('adresa', 500);
            $table->string('opcina', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            // Klasifikacija
            $table->string('tip_nepogode', 50);
            $table->string('ugrozenost_ljudi', 20)->default('ne_znam'); // da, ne, ne_znam
            $table->string('prioritet', 20)->default('standardna'); // kriticna, visoka, standardna
            
            // Sadržaj
            $table->text('opis')->nullable();
            
            // Status
            $table->string('status', 30)->default('zaprimljena'); // zaprimljena, dodijeljena, u_tijeku, zavrsena
            
            $table->timestamps();
            
            // Indeksi za brze pretrage
            $table->index('broj_dojave');
            $table->index('status');
            $table->index('prioritet');
            $table->index('vrijeme_zaprimanja');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dojavas');
    }
};