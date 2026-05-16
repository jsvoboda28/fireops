<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimVozilo extends Model
{
    use HasFactory;

    protected $table = 'tim_vozila';

    protected $fillable = [
        'tim_id',
        'vozilo_id',
        'dodano_u',
        'skinuto_u',
        'napomena',
    ];

    protected $casts = [
        'dodano_u' => 'datetime',
        'skinuto_u' => 'datetime',
    ];

    public function tim(): BelongsTo
    {
        return $this->belongsTo(Tim::class);
    }

    public function vozilo(): BelongsTo
    {
        return $this->belongsTo(Vozilo::class);
    }

    public function scopeAktivno($query)
    {
        return $query->whereNull('skinuto_u');
    }

    public function jeAktivno(): bool
    {
        return $this->skinuto_u === null;
    }
}