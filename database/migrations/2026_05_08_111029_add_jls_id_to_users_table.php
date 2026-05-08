<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pripadnost JLS-u (npr. dispečer iz Pakraca)
            // Nullable jer super_admin i voditelj_zupanije nemaju JLS
            $table->foreignId('jls_id')
                ->nullable()
                ->after('email')
                ->constrained('jls')
                ->nullOnDelete();
            
            // Status korisnika (aktivan/neaktivan)
            $table->boolean('aktivan')
                ->default(true)
                ->after('jls_id');
            
            // Indeksi
            $table->index('jls_id');
            $table->index('aktivan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['jls_id']);
            $table->dropColumn(['jls_id', 'aktivan']);
        });
    }
};