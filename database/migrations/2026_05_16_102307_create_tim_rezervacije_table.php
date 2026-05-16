<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim_rezervacije', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('tim_id')
                ->constrained('timovi')
                ->cascadeOnDelete();
            
            $table->foreignId('intervencija_id')
                ->constrained('intervencije')
                ->cascadeOnDelete();
            
            // Redni broj u redu čekanja za ovaj tim (1 = prvi, 2 = drugi...)
            $table->integer('redni_broj')->default(1);
            
            $table->foreignId('rezervirao_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->timestamp('rezervirano_u');
            
            // Kada je rezervacija aktivirana (tim premjesten na ovu intervenciju)
            $table->timestamp('aktivirano_u')->nullable();
            
            // Kada je otkazana (ako je)
            $table->timestamp('otkazano_u')->nullable();
            
            // Razlog otkazivanja
            $table->string('razlog_otkazivanja', 100)->nullable();
            // 'rucno', 'tim_raspusten', 'intervencija_zatvorena'
            
            $table->text('napomena')->nullable();
            
            $table->timestamps();
            
            $table->index(['tim_id', 'redni_broj']);
            $table->index('intervencija_id');
            $table->index('aktivirano_u');
            $table->index('otkazano_u');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_rezervacije');
    }
};