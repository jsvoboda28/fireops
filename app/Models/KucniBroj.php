<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KucniBroj extends Model
{
    protected $table = 'kucni_brojevi';
    
    protected $fillable = [
        'kb_jid',
        'broj',
        'ulica_id',
        'naselje_id',
        'latitude',
        'longitude',
    ];
    
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
    
    public function ulica(): BelongsTo
    {
        return $this->belongsTo(Ulica::class);
    }
    
    public function naselje(): BelongsTo
    {
        return $this->belongsTo(Naselje::class);
    }
    
    /**
     * Puna adresa kao string.
     * Primjer: "Industrijska 12, Pakrac"
     */
    public function getPunaAdresaAttribute(): string
    {
        $parts = [];
        if ($this->ulica) {
            $parts[] = $this->ulica->naziv . ' ' . $this->broj;
        } else {
            $parts[] = $this->broj;
        }
        if ($this->naselje) {
            $parts[] = $this->naselje->naziv;
        }
        return implode(', ', $parts);
    }
}