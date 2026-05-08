<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dojavas', function (Blueprint $table) {
            // JLS kojem pripada dojava
            $table->foreignId('jls_id')
                ->nullable()
                ->after('opcina')
                ->constrained('jls')
                ->nullOnDelete();
            
            $table->index('jls_id');
        });
    }

    public function down(): void
    {
        Schema::table('dojavas', function (Blueprint $table) {
            $table->dropForeign(['jls_id']);
            $table->dropColumn('jls_id');
        });
    }
};