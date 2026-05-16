<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dojavas', function (Blueprint $table) {
            $table->foreignId('intervencija_id')
                ->nullable()
                ->after('dogadjaj_id')
                ->constrained('intervencije')
                ->nullOnDelete();
            
            $table->index('intervencija_id');
        });
    }

    public function down(): void
    {
        Schema::table('dojavas', function (Blueprint $table) {
            $table->dropForeign(['intervencija_id']);
            $table->dropIndex(['intervencija_id']);
            $table->dropColumn('intervencija_id');
        });
    }
};