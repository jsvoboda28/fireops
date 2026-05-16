<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim_clanstvo', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('tim_id')
                ->constrained('timovi')
                ->cascadeOnDelete();
            
            $table->foreignId('vatrogasac_id')
                ->constrained('vatrogasci')
                ->cascadeOnDelete();
            
            $table->string('uloga', 20)->default('vatrogasac');
            
            $table->timestamp('usao_u');
            $table->timestamp('izasao_u')->nullable();
            
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            $table->index('tim_id');
            $table->index('vatrogasac_id');
            $table->index('izasao_u');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_clanstvo');
    }
};