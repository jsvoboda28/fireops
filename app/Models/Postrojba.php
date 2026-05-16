<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Postrojba extends Model
{
    protected $table = 'postrojbe';
    
    protected $fillable = [
        'naziv',
        'skraceni_naziv',
        'tip',
        'kategorija',
        'jls_id',
        'oib',
        'mb',
        'adresa',
        'mjesto',
        'telefon',
        'mobitel',
        'email',
        'web',
        'latitude',
        'longitude',
        'aktivna',
        'operativno_spremna',
        'broj_clanova',
        'broj_operativnih',
        'napomena',
    ];
    
    protected $casts = [
        'aktivna' => 'boolean',
        'operativno_spremna' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
    
    /**
     * JLS kojem postrojba pripada.
     */
    public function jls(): BelongsTo
    {
        return $this->belongsTo(Jls::class);
    }
    
    /**
     * Svi vatrogasci u postrojbi.
     */
    public function vatrogasci(): HasMany
    {
        return $this->hasMany(Vatrogasac::class);
    }
    
    /**
     * Sva vozila u postrojbi.
     */
    public function vozila(): HasMany
    {
        return $this->hasMany(Vozilo::class);
    }

    /**
     * Timovi koji su trenutno u bazi ove postrojbe.
     */
    public function timoviUBazi(): HasMany
    {
        return $this->hasMany(Tim::class, 'baza_postrojba_id');
    }
    
    /**
     * Stvarni broj članova.
     */
    public function getStvarniBrojClanovaAttribute(): int
    {
        return $this->vatrogasci()->count();
    }
    
    /**
     * Stvarni broj operativnih vatrogasaca.
     */
    public function getStvarniBrojOperativnihAttribute(): int
    {
        return $this->vatrogasci()
            ->where('operativan', true)
            ->where('status', 'aktivan')
            ->count();
    }
}