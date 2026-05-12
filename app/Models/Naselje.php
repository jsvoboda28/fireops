<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Naselje extends Model
{
    protected $table = 'naselja';
    
    protected $fillable = [
        'na_mb',
        'naziv',
        'jls_id',
        'jls_ime_rpj',
        'postanski_broj',
    ];
    
    public function jls(): BelongsTo
    {
        return $this->belongsTo(Jls::class);
    }
    
    public function ulice(): HasMany
    {
        return $this->hasMany(Ulica::class);
    }
    
    public function kucniBrojevi(): HasMany
    {
        return $this->hasMany(KucniBroj::class);
    }
}