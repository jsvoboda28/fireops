<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dojava extends Model
{
    protected $table = 'dojavas';
    
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
        'jls_id',
        'dogadjaj_id',
        'latitude',
        'longitude',
        'tip_nepogode',
        'ugrozenost_ljudi',
        'prioritet',
        'opis',
        'status',
    ];
    
    protected $casts = [
        'vrijeme_zaprimanja' => 'datetime',
        'vrijeme_zatvaranja' => 'datetime',
        'prijavitelj_anoniman' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
    
    /**
     * Operater (User) koji je zaprimio dojavu.
     */
    public function operater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operater_id');
    }
    
    /**
     * JLS kojem pripada dojava.
     */
    public function jls(): BelongsTo
    {
        return $this->belongsTo(Jls::class);
    }
    
    /**
     * Operativni događaj kojem dojava pripada.
     */
    public function dogadjaj(): BelongsTo
    {
        return $this->belongsTo(OperativniDogadjaj::class, 'dogadjaj_id');
    }
}