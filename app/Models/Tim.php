<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tim extends Model
{
    use HasFactory;

    protected $table = 'timovi';

    protected $fillable = [
        'naziv',
        'intervencija_id',
        'baza_postrojba_id',
        'zapovjednik_id',
        'trenutni_status',
        'zadatak',
        'vrijeme_formiranja',
        'vrijeme_raspustanja',
        'napomena',
    ];

    protected $casts = [
        'vrijeme_formiranja' => 'datetime',
        'vrijeme_raspustanja' => 'datetime',
    ];

    // RELACIJE

    public function intervencija(): BelongsTo
    {
        return $this->belongsTo(Intervencija::class);
    }

    public function bazaPostrojba(): BelongsTo
    {
        return $this->belongsTo(Postrojba::class, 'baza_postrojba_id');
    }

    public function zapovjednik(): BelongsTo
    {
        return $this->belongsTo(Vatrogasac::class, 'zapovjednik_id');
    }

    public function clanstvo(): HasMany
    {
        return $this->hasMany(TimClanstvo::class);
    }

    public function trenutniClanovi(): HasMany
    {
        return $this->hasMany(TimClanstvo::class)->whereNull('izasao_u');
    }

    public function vozila(): HasMany
    {
        return $this->hasMany(TimVozilo::class);
    }

    public function trenutnaVozila(): HasMany
    {
        return $this->hasMany(TimVozilo::class)->whereNull('skinuto_u');
    }

    public function statusLog(): HasMany
    {
        return $this->hasMany(TimStatusLog::class)->orderBy('vrijeme', 'desc');
    }

    /**
     * Sve rezervacije (povijest + aktivne).
     */
    public function rezervacije(): HasMany
    {
        return $this->hasMany(TimRezervacija::class);
    }

    /**
     * Aktivne rezervacije (red čekanja).
     */
    public function aktivneRezervacije(): HasMany
    {
        return $this->hasMany(TimRezervacija::class)
            ->whereNull('aktivirano_u')
            ->whereNull('otkazano_u')
            ->orderBy('redni_broj');
    }

    // SCOPE

    public function scopeAktivni($query)
    {
        return $query->where('trenutni_status', '!=', 'raspusten');
    }

    public function scopeNaIntervenciji($query)
    {
        return $query->whereNotNull('intervencija_id')
            ->where('trenutni_status', '!=', 'raspusten');
    }

    public function scopeUBazi($query)
    {
        return $query->whereIn('trenutni_status', ['cekanje_u_bazi', 'odmor']);
    }

    // HELPERI

    public function brojClanova(): int
    {
        return $this->trenutniClanovi()->count();
    }

    public function brojVozila(): int
    {
        return $this->trenutnaVozila()->count();
    }

    public function jeAktivan(): bool
    {
        return $this->trenutni_status !== 'raspusten';
    }
}