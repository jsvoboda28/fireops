<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vatrogasci', function (Blueprint $table) {
            $table->id();
            
            // Osobni podaci
            $table->string('ime', 100);
            $table->string('prezime', 100);
            $table->string('oib', 11)->nullable()->unique();
            $table->date('datum_rodjenja')->nullable();
            
            // Pripadnost
            $table->foreignId('postrojba_id')->nullable()->constrained('postrojbe')->nullOnDelete();
            
            // Kategorija (operativna spremnost)
            $table->string('kategorija', 30)->default('vatrogasac');
            // vatrogasac, vatrogasac_I, vatrogasac_II, docasnik, casnik, visi_casnik
            
            // Specijalnosti (JSON polje s listom)
            $table->json('specijalnosti')->nullable();
            
            // Kontakt
            $table->string('mobitel', 50)->nullable();
            $table->string('email', 200)->nullable();
            
            // PIN za mobilnu autentifikaciju (hashiran)
            $table->string('pin_hash', 255)->nullable();
            
            // Status
            $table->string('status', 20)->default('aktivan');
            // aktivan, mirovanje, neaktivan, suspenzija
            
            $table->boolean('operativan')->default(true);
            $table->date('datum_pristupa')->nullable();
            
            // Napomena
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            // Indeksi
            $table->index('prezime');
            $table->index('postrojba_id');
            $table->index('status');
            $table->index('operativan');
            $table->index('kategorija');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vatrogasci');
    }
};