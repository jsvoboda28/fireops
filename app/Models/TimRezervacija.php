<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimRezervacija extends Model
{
    use HasFactory;

    protected $table = 'tim_rezervacije';

    protected $fillable = [
        'tim_id',
        'intervencija_id',
        'redni_broj',
        'rezervirao_id',
        'rezervirano_u',
        'aktivirano_u',
        'otkazano_u',
        'razlog_otkazivanja',
        'napomena',
    ];

    protected $casts = [
        'rezervirano_u' => 'datetime',
        'aktivirano_u' => 'datetime',
        'otkazano_u' => 'datetime',
    ];

    // RELACIJE

    public function tim(): BelongsTo
    {
        return $this->belongsTo(Tim::class);
    }

    public function intervencija(): BelongsTo
    {
        return $this->belongsTo(Intervencija::class);
    }

    public function rezervirao(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rezervirao_id');
    }

    // SCOPE

    /**
     * Aktivne rezervacije (čekaju aktivaciju).
     */
    public function scopeAktivne($query)
    {
        return $query->whereNull('aktivirano_u')
                     ->whereNull('otkazano_u');
    }

    /**
     * Rezervacije za određeni tim (red čekanja).
     */
    public function scopeZaTim($query, int $timId)
    {
        return $query->where('tim_id', $timId)
                     ->whereNull('aktivirano_u')
                     ->whereNull('otkazano_u')
                     ->orderBy('redni_broj');
    }

    // HELPERI

    public function jeAktivna(): bool
    {
        return is_null($this->aktivirano_u) && is_null($this->otkazano_u);
    }

    public function jeAktivirana(): bool
    {
        return !is_null($this->aktivirano_u);
    }

    public function jeOtkazana(): bool
    {
        return !is_null($this->otkazano_u);
    }
}