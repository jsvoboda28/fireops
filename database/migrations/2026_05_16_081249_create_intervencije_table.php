<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervencije', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('operativni_dogadjaj_id')
                ->nullable()
                ->constrained('operativni_dogadjaji')
                ->nullOnDelete();
            
            $table->foreignId('pocetna_dojava_id')
                ->nullable()
                ->constrained('dojavas')
                ->nullOnDelete();
            
            $table->string('broj')->unique();
            $table->string('naziv', 300);
            $table->text('opis')->nullable();
            
            $table->string('adresa', 500)->nullable();
            $table->foreignId('jls_id')->nullable()->constrained('jls')->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            $table->string('tip_intervencije', 50)->default('ostalo');
            $table->string('prioritet', 20)->default('standardna');
            $table->string('status', 30)->default('aktivna');
            
            $table->foreignId('voditelj_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->timestamp('vrijeme_otvaranja');
            $table->timestamp('vrijeme_zatvaranja')->nullable();
            
            $table->timestamps();
            
            $table->index('status');
            $table->index('prioritet');
            $table->index('vrijeme_otvaranja');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervencije');
    }
};