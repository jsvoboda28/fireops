<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vatrogasne_zajednice', function (Blueprint $table) {
            $table->id();
            
            $table->string('naziv', 200);
            $table->string('skraceni_naziv', 50)->nullable();
            $table->string('tip', 30); // zupanijska, podrucna, opcinska
            
            // Hijerarhija — područna VZ ima parent = županijska VZ
            $table->foreignId('roditelj_id')->nullable()->constrained('vatrogasne_zajednice')->nullOnDelete();
            
            // Kontakt
            $table->string('oib', 11)->nullable();
            $table->string('adresa', 300)->nullable();
            $table->string('telefon', 50)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('web', 200)->nullable();
            
            $table->boolean('aktivna')->default(true);
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            $table->index('tip');
            $table->index('aktivna');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vatrogasne_zajednice');
    }
};