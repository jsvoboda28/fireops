<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operativni_dogadjaji', function (Blueprint $table) {
            $table->id();
            
            // Identifikacija
            $table->string('naziv', 200);
            $table->text('opis')->nullable();
            
            // Razina
            $table->string('razina', 20)->default('lokalna'); // lokalna, zupanijska
            $table->foreignId('jls_id')->nullable()->constrained('jls')->nullOnDelete();
            
            // Vremena
            $table->timestamp('vrijeme_otvaranja');
            $table->timestamp('vrijeme_zatvaranja')->nullable();
            
            // Status
            $table->string('status', 20)->default('aktivan'); // praćenje, aktivan, zatvoren
            $table->string('stupanj_sukoba', 10)->nullable(); // I, II, III, IV
            
            // Tko je otvorio
            $table->foreignId('otvorio_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('zatvorio_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Tip nepogode (glavni)
            $table->string('tip_nepogode', 50);
            
            // Voditelj događaja
            $table->foreignId('voditelj_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Sažetak (koji se popunjava kad se zatvara)
            $table->text('zavrsni_sazetak')->nullable();
            
            $table->timestamps();
            
            // Indeksi
            $table->index('status');
            $table->index('razina');
            $table->index('vrijeme_otvaranja');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operativni_dogadjaji');
    }
};