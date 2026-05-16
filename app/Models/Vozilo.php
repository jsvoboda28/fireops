<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vozilo extends Model
{
    protected $table = 'vozila';
    
    protected $fillable = [
        'registracija',
        'marka',
        'model',
        'godina_proizvodnje',
        'postrojba_id',
        'tip',
        'namjena',
        'kapacitet_vode',
        'kapacitet_pjenila',
        'broj_sjedala',
        'snaga_pumpe',
        'status',
        'aktivno',
        'tehnicki_pregled_do',
        'registrirano_do',
        'latitude',
        'longitude',
        'napomena',
    ];
    
    protected $casts = [
        'aktivno' => 'boolean',
        'tehnicki_pregled_do' => 'date',
        'registrirano_do' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
    
    /**
     * Postrojba kojoj vozilo pripada.
     */
    public function postrojba(): BelongsTo
    {
        return $this->belongsTo(Postrojba::class);
    }

    /**
     * Povijest dodjela u timove.
     */
    public function timoviClanstvo(): HasMany
    {
        return $this->hasMany(TimVozilo::class);
    }

    /**
     * Trenutni timovi (gdje je vozilo trenutno).
     */
    public function trenutniTimovi(): HasMany
    {
        return $this->hasMany(TimVozilo::class)->whereNull('skinuto_u');
    }
    
    /**
     * Provjera da li tehnički ističe za manje od 30 dana.
     */
    public function getTehnickiUskorAttribute(): bool
    {
        if (!$this->tehnicki_pregled_do) {
            return false;
        }
        return $this->tehnicki_pregled_do->diffInDays(now()) <= 30 
            && $this->tehnicki_pregled_do->isFuture();
    }
    
    /**
     * Provjera da li je tehnički istekao.
     */
    public function getTehnickiIstekaoAttribute(): bool
    {
        if (!$this->tehnicki_pregled_do) {
            return false;
        }
        return $this->tehnicki_pregled_do->isPast();
    }

    /**
     * Da li je vozilo trenutno u nekom timu.
     */
    public function jeUTimu(): bool
    {
        return $this->trenutniTimovi()->exists();
    }
}