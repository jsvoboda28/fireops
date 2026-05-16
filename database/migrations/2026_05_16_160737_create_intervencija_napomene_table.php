<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervencija_napomene', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervencija_id')
                ->constrained('intervencije')
                ->cascadeOnDelete();
            $table->foreignId('autor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->enum('tip', [
                'biljeska',     // 📝 Opća bilješka
                'opasnost',     // ⚠ Opasnost
                'radio',        // 📞 Radio poruka
                'zahtjev',      // 🔧 Zahtjev za resurs
                'akcija',       // ✅ Akcija izvršena
                'lokacija',     // 📍 Lokacijska info
            ])->default('biljeska');
            $table->text('sadrzaj');
            $table->timestamp('vrijeme');
            $table->timestamps();
            
            $table->index(['intervencija_id', 'vrijeme']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervencija_napomene');
    }
};