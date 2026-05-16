<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim_status_log', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('tim_id')
                ->constrained('timovi')
                ->cascadeOnDelete();
            
            $table->string('status', 30);
            $table->timestamp('vrijeme');
            
            $table->foreignId('autor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->foreignId('intervencija_id')
                ->nullable()
                ->constrained('intervencije')
                ->nullOnDelete();
            
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            $table->index('tim_id');
            $table->index('vrijeme');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_status_log');
    }
};