<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dojavas', function (Blueprint $table) {
            $table->foreignId('dogadjaj_id')
                ->nullable()
                ->after('jls_id')
                ->constrained('operativni_dogadjaji')
                ->nullOnDelete();
            
            $table->index('dogadjaj_id');
        });
    }

    public function down(): void
    {
        Schema::table('dojavas', function (Blueprint $table) {
            $table->dropForeign(['dogadjaj_id']);
            $table->dropColumn('dogadjaj_id');
        });
    }
};