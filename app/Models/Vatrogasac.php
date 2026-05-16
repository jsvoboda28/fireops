<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Vatrogasac extends Model
{
    protected $table = 'vatrogasci';

    protected $fillable = [
        'ime',
        'prezime',
        'oib',
        'datum_rodjenja',
        'postrojba_id',
        'kategorija',
        'specijalnosti',
        'mobitel',
        'email',
        'pin_hash',
        'status',
        'operativan',
        'datum_pristupa',
        'napomena',
    ];

    protected $hidden = [
        'pin_hash',
    ];

    protected $casts = [
        'datum_rodjenja' => 'date',
        'datum_pristupa' => 'date',
        'specijalnosti' => 'array',
        'operativan' => 'boolean',
    ];

    /**
     * Postrojba kojoj vatrogasac pripada (matična).
     */
    public function postrojba(): BelongsTo
    {
        return $this->belongsTo(Postrojba::class);
    }

    /**
     * Sva članstva vatrogasca u timovima (povijest).
     */
    public function clanstvo(): HasMany
    {
        return $this->hasMany(TimClanstvo::class);
    }

    /**
     * Trenutni timovi (gdje vatrogasac trenutno aktivno radi).
     */
    public function trenutniTimovi(): HasMany
    {
        return $this->hasMany(TimClanstvo::class)->whereNull('izasao_u');
    }

    /**
     * Timovi koje vodi (kao zapovjednik).
     */
    public function timoviKaoZapovjednik(): HasMany
    {
        return $this->hasMany(Tim::class, 'zapovjednik_id');
    }

    /**
     * Puno ime.
     */
    public function getPunoImeAttribute(): string
    {
        return trim($this->ime . ' ' . $this->prezime);
    }

    /**
     * Postavi PIN (hashira ga).
     */
    public function setPin(string $pin): void
    {
        $this->pin_hash = Hash::make($pin);
        $this->save();
    }

    /**
     * Provjeri PIN.
     */
    public function checkPin(string $pin): bool
    {
        if (empty($this->pin_hash)) {
            return false;
        }
        return Hash::check($pin, $this->pin_hash);
    }

    /**
     * Da li je vatrogasac trenutno u nekom timu.
     */
    public function jeUTimu(): bool
    {
        return $this->trenutniTimovi()->exists();
    }
}