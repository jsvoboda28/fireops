<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intervencija extends Model
{
    use HasFactory;

    protected $table = 'intervencije';

    protected $fillable = [
        'operativni_dogadjaj_id',
        'pocetna_dojava_id',
        'broj',
        'naziv',
        'opis',
        'adresa',
        'jls_id',
        'latitude',
        'longitude',
        'tip_intervencije',
        'prioritet',
        'status',
        'voditelj_id',
        'vrijeme_otvaranja',
        'vrijeme_zatvaranja',
    ];

    protected $casts = [
        'vrijeme_otvaranja' => 'datetime',
        'vrijeme_zatvaranja' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    // RELACIJE

    public function operativniDogadjaj(): BelongsTo
    {
        return $this->belongsTo(OperativniDogadjaj::class);
    }

    public function pocetnaDojava(): BelongsTo
    {
        return $this->belongsTo(Dojava::class, 'pocetna_dojava_id');
    }

    public function dojave(): HasMany
    {
        return $this->hasMany(Dojava::class, 'intervencija_id');
    }

    public function jls(): BelongsTo
    {
        return $this->belongsTo(Jls::class);
    }

    public function voditelj(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voditelj_id');
    }

    public function timovi(): HasMany
    {
        return $this->hasMany(Tim::class);
    }

    public function statusLog(): HasMany
    {
        return $this->hasMany(TimStatusLog::class);
    }

    /**
     * Rezervacije timova za ovu intervenciju.
     */
    public function rezervacije(): HasMany
    {
        return $this->hasMany(TimRezervacija::class);
    }

    /**
     * Aktivne rezervacije (timovi čekaju da budu prebačeni).
     */
    public function aktivneRezervacije(): HasMany
    {
        return $this->hasMany(TimRezervacija::class)
            ->whereNull('aktivirano_u')
            ->whereNull('otkazano_u')
            ->orderBy('rezervirano_u');
    }

    /**
     * Napomene / dnevnik / opažanja za ovu intervenciju.
     */
    public function napomene(): HasMany
    {
        return $this->hasMany(IntervencijaNapomena::class)->orderBy('vrijeme', 'desc');
    }

    // SCOPE

    public function scopeAktivne($query)
    {
        return $query->where('status', 'aktivna');
    }

    // HELPERI

    public function jeAktivna(): bool
    {
        return $this->status === 'aktivna';
    }

    public function brojAngazirakihTimova(): int
    {
        return $this->timovi()
            ->where('trenutni_status', '!=', 'raspusten')
            ->count();
    }
}