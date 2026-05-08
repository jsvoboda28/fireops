<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}