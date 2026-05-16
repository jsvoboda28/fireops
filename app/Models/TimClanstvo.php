<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimClanstvo extends Model
{
    use HasFactory;

    protected $table = 'tim_clanstvo';

    protected $fillable = [
        'tim_id',
        'vatrogasac_id',
        'uloga',
        'usao_u',
        'izasao_u',
        'napomena',
    ];

    protected $casts = [
        'usao_u' => 'datetime',
        'izasao_u' => 'datetime',
    ];

    // RELACIJE

    public function tim(): BelongsTo
    {
        return $this->belongsTo(Tim::class);
    }

    public function vatrogasac(): BelongsTo
    {
        return $this->belongsTo(Vatrogasac::class);
    }

    // SCOPE

    public function scopeAktivno($query)
    {
        return $query->whereNull('izasao_u');
    }

    // HELPERI

    public function jeAktivno(): bool
    {
        return $this->izasao_u === null;
    }

    public function trajanjeMinuta(): int
    {
        $kraj = $this->izasao_u ?? now();
        return (int) $this->usao_u->diffInMinutes($kraj);
    }
}