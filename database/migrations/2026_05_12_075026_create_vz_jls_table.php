<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vz_jls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vz_id')->constrained('vatrogasne_zajednice')->cascadeOnDelete();
            $table->foreignId('jls_id')->constrained('jls')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['vz_id', 'jls_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vz_jls');
    }
};