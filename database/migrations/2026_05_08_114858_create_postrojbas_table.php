<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postrojbe', function (Blueprint $table) {
            $table->id();
            
            // Identifikacija
            $table->string('naziv', 200);
            $table->string('skraceni_naziv', 50)->nullable(); // npr. "DVD Pakrac"
            $table->string('tip', 20); // dvd, jvp, ostalo
            $table->string('kategorija', 10)->nullable(); // I, II, III (za DVD), JVP nema
            
            // Pripadnost
            $table->foreignId('jls_id')->nullable()->constrained('jls')->nullOnDelete();
            
            // Identifikacijski podaci
            $table->string('oib', 11)->nullable();
            $table->string('mb', 20)->nullable();
            
            // Kontakt
            $table->string('adresa', 200)->nullable();
            $table->string('mjesto', 100)->nullable();
            $table->string('telefon', 50)->nullable();
            $table->string('mobitel', 50)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('web', 200)->nullable();
            
            // Lokacija (za mapu)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            // Operativno
            $table->boolean('aktivna')->default(true);
            $table->boolean('operativno_spremna')->default(true);
            $table->integer('broj_clanova')->default(0);
            $table->integer('broj_operativnih')->default(0);
            
            // Napomena
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            // Indeksi
            $table->index('naziv');
            $table->index('tip');
            $table->index('kategorija');
            $table->index('aktivna');
            $table->index('jls_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postrojbe');
    }
};