<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ulica extends Model
{
    protected $table = 'ulice';
    
    protected $fillable = [
        'ul_jid',
        'naziv',
        'naselje_id',
    ];
    
    public function naselje(): BelongsTo
    {
        return $this->belongsTo(Naselje::class);
    }
    
    public function kucniBrojevi(): HasMany
    {
        return $this->hasMany(KucniBroj::class);
    }
}