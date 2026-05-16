<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim_vozila', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('tim_id')
                ->constrained('timovi')
                ->cascadeOnDelete();
            
            $table->foreignId('vozilo_id')
                ->constrained('vozila')
                ->cascadeOnDelete();
            
            $table->timestamp('dodano_u');
            $table->timestamp('skinuto_u')->nullable();
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            $table->index('tim_id');
            $table->index('vozilo_id');
            $table->index('skinuto_u');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_vozila');
    }
};