<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kucni_brojevi', function (Blueprint $table) {
            $table->id();
            
            // RPJ identifikatori
            $table->string('kb_jid', 50)->nullable()->unique(); // jedinstveni ID iz RPJ
            
            // Adresa
            $table->string('broj', 20); // npr. "12", "12A", "12/B"
            $table->foreignId('ulica_id')->nullable()->constrained('ulice')->nullOnDelete();
            $table->foreignId('naselje_id')->nullable()->constrained('naselja')->nullOnDelete();
            
            // GPS koordinate (decimal za brze upite)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            $table->timestamps();
            
            $table->index('broj');
            $table->index(['ulica_id', 'broj']);
            $table->index(['latitude', 'longitude']);
        });
        
        // PostGIS točka kolona za prostorne upite
        DB::statement('ALTER TABLE kucni_brojevi ADD COLUMN geom geometry(Point, 4326)');
        DB::statement('CREATE INDEX kucni_brojevi_geom_idx ON kucni_brojevi USING GIST (geom)');
    }

    public function down(): void
    {
        Schema::dropIfExists('kucni_brojevi');
    }
};