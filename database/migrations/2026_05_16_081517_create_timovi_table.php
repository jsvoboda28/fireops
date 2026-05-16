<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timovi', function (Blueprint $table) {
            $table->id();
            $table->string('naziv', 100);
            
            $table->foreignId('intervencija_id')
                ->nullable()
                ->constrained('intervencije')
                ->nullOnDelete();
            
            $table->foreignId('baza_postrojba_id')
                ->nullable()
                ->constrained('postrojbe')
                ->nullOnDelete();
            
            $table->foreignId('zapovjednik_id')
                ->nullable()
                ->constrained('vatrogasci')
                ->nullOnDelete();
            
            $table->string('trenutni_status', 30)->default('formiran');
            $table->text('zadatak')->nullable();
            
            $table->timestamp('vrijeme_formiranja');
            $table->timestamp('vrijeme_raspustanja')->nullable();
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            $table->index('trenutni_status');
            $table->index('intervencija_id');
            $table->index('baza_postrojba_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timovi');
    }
};