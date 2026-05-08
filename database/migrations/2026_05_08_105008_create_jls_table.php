<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jls', function (Blueprint $table) {
            $table->id();
            
            // Osnovni podaci
            $table->string('naziv', 100);
            $table->string('tip', 20); // grad, opcina
            $table->string('zupanija', 100)->default('Požeško-slavonska');
            
            // Identifikacija
            $table->string('mb', 20)->nullable();      // matični broj
            $table->string('oib', 11)->nullable();     // OIB
            
            // Kontakt
            $table->string('adresa', 200)->nullable();
            $table->string('telefon', 50)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('web', 200)->nullable();
            
            // Operativno
            $table->boolean('aktivan')->default(true);
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            // Indeksi
            $table->index('naziv');
            $table->index('tip');
            $table->index('aktivan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jls');
    }
};