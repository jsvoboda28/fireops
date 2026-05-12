<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulice', function (Blueprint $table) {
            $table->id();
            
            // RPJ identifikatori
            $table->string('ul_jid', 50)->nullable()->unique(); // jedinstveni ID ulice iz RPJ
            
            // Osnovni podaci
            $table->string('naziv', 300);
            $table->foreignId('naselje_id')->nullable()->constrained('naselja')->nullOnDelete();
            
            $table->timestamps();
            
            $table->index('naziv');
            $table->index('naselje_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulice');
    }
};