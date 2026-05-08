<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vozila', function (Blueprint $table) {
            $table->id();
            
            // Identifikacija
            $table->string('registracija', 20)->unique();
            $table->string('marka', 100);
            $table->string('model', 100)->nullable();
            $table->integer('godina_proizvodnje')->nullable();
            
            // Pripadnost
            $table->foreignId('postrojba_id')->nullable()->constrained('postrojbe')->nullOnDelete();
            
            // Kategorija/tip
            $table->string('tip', 30); // navalno, autocisterna, sumsko, kombi, tehnicko, terensko, ljestve, ostalo
            $table->string('namjena', 100)->nullable(); // dodatni opis namjene
            
            // Kapaciteti
            $table->integer('kapacitet_vode')->nullable(); // u litrama
            $table->integer('kapacitet_pjenila')->nullable(); // u litrama
            $table->integer('broj_sjedala')->nullable();
            $table->integer('snaga_pumpe')->nullable(); // l/min
            
            // Status
            $table->string('status', 30)->default('operativno');
            // operativno, na_servisu, u_kvaru, povučeno
            
            $table->boolean('aktivno')->default(true);
            
            // Tehnički pregled
            $table->date('tehnicki_pregled_do')->nullable();
            $table->date('registrirano_do')->nullable();
            
            // Lokacija (opcionalno, ako vozilo nije u svom DVD-u)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            // Napomena
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            // Indeksi
            $table->index('registracija');
            $table->index('postrojba_id');
            $table->index('tip');
            $table->index('status');
            $table->index('aktivno');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vozila');
    }
};