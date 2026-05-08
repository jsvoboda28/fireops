<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperativniDogadjaj extends Model
{
    protected $table = 'operativni_dogadjaji';
    
    protected $fillable = [
        'naziv',
        'opis',
        'razina',
        'jls_id',
        'vrijeme_otvaranja',
        'vrijeme_zatvaranja',
        'status',
        'stupanj_sukoba',
        'otvorio_id',
        'zatvorio_id',
        'tip_nepogode',
        'voditelj_id',
        'zavrsni_sazetak',
    ];
    
    protected $casts = [
        'vrijeme_otvaranja' => 'datetime',
        'vrijeme_zatvaranja' => 'datetime',
    ];
    
    /**
     * JLS kojem događaj pripada (lokalni događaj).
     */
    public function jls(): BelongsTo
    {
        return $this->belongsTo(Jls::class);
    }
    
    /**
     * Korisnik koji je otvorio događaj.
     */
    public function otvorio(): BelongsTo
    {
        return $this->belongsTo(User::class, 'otvorio_id');
    }
    
    /**
     * Korisnik koji je zatvorio događaj.
     */
    public function zatvorio(): BelongsTo
    {
        return $this->belongsTo(User::class, 'zatvorio_id');
    }
    
    /**
     * Voditelj događaja.
     */
    public function voditelj(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voditelj_id');
    }
    
    /**
     * Sve dojave koje pripadaju ovom događaju.
     */
    public function dojave(): HasMany
    {
        return $this->hasMany(Dojava::class, 'dogadjaj_id');
    }
    
    /**
     * Trajanje događaja u satima i minutama.
     */
    public function getTrajanjeAttribute(): string
    {
        $kraj = $this->vrijeme_zatvaranja ?? now();
        $diff = $this->vrijeme_otvaranja->diff($kraj);
        
        if ($diff->d > 0) {
            return $diff->d . 'd ' . $diff->h . 'h ' . $diff->i . 'min';
        }
        return $diff->h . 'h ' . $diff->i . 'min';
    }
}