<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dojava extends Model
{
    // Hrvatska množina za naziv tablice
    protected $table = 'dojavas';
    
    // Sva polja koja se mogu masovno dodjeljivati
    protected $fillable = [
        'broj_dojave',
        'vrijeme_zaprimanja',
        'vrijeme_zatvaranja',
        'kanal_dojave',
        'operater_id',
        'prijavitelj_ime',
        'prijavitelj_telefon',
        'prijavitelj_anoniman',
        'adresa',
        'opcina',
        'latitude',
        'longitude',
        'tip_nepogode',
        'ugrozenost_ljudi',
        'prioritet',
        'opis',
        'status',
    ];
    
    // Tipovi podataka
    protected $casts = [
        'vrijeme_zaprimanja' => 'datetime',
        'vrijeme_zatvaranja' => 'datetime',
        'prijavitelj_anoniman' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
    
    // Veza s operaterom (User koji je zaprimio)
    public function operater()
    {
        return $this->belongsTo(User::class, 'operater_id');
    }
}