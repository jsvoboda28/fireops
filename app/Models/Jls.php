<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jls extends Model
{
    protected $table = 'jls';
    
    protected $fillable = [
        'naziv',
        'tip',
        'zupanija',
        'mb',
        'oib',
        'adresa',
        'telefon',
        'email',
        'web',
        'aktivan',
        'napomena',
    ];
    
    protected $casts = [
        'aktivan' => 'boolean',
    ];
    
    /**
     * Sve postrojbe koje pripadaju ovom JLS-u.
     */
    public function postrojbe(): HasMany
    {
        return $this->hasMany(Postrojba::class);
    }
    
    /**
     * Sve dojave u ovom JLS-u.
     */
    public function dojave(): HasMany
    {
        return $this->hasMany(Dojava::class);
    }
    
    /**
     * Svi korisnici koji pripadaju ovom JLS-u.
     */
    public function korisnici(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Vatrogasne zajednice kojima ovaj JLS pripada.
     */
    public function vatrogasneZajednice(): BelongsToMany
    {
        return $this->belongsToMany(VatrogasnaZajednica::class, 'vz_jls', 'jls_id', 'vz_id')->withTimestamps();
    }
}