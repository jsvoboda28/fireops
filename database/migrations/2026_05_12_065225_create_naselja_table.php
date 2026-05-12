<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naselja', function (Blueprint $table) {
            $table->id();
            
            // RPJ identifikatori
            $table->string('na_mb', 20)->nullable()->index(); // matični broj naselja iz RPJ
            
            // Osnovni podaci
            $table->string('naziv', 200);
            $table->foreignId('jls_id')->nullable()->constrained('jls')->nullOnDelete();
            $table->string('jls_ime_rpj', 200)->nullable(); // originalno ime JLS iz RPJ
            
            // Poštanski broj (iz prvog kućnog broja u naselju)
            $table->string('postanski_broj', 10)->nullable();
            
            $table->timestamps();
            
            $table->index('naziv');
            $table->index('postanski_broj');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naselja');
    }
};