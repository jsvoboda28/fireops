<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntervencijaNapomena extends Model
{
    use HasFactory;

    protected $table = 'intervencija_napomene';

    protected $fillable = [
        'intervencija_id',
        'autor_id',
        'tip',
        'sadrzaj',
        'vrijeme',
    ];

    protected $casts = [
        'vrijeme' => 'datetime',
    ];

    public function intervencija(): BelongsTo
    {
        return $this->belongsTo(Intervencija::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'autor_id');
    }

    public function getTipIkonaAttribute(): string
    {
        return match($this->tip) {
            'biljeska' => '📝',
            'opasnost' => '⚠',
            'radio' => '📞',
            'zahtjev' => '🔧',
            'akcija' => '✅',
            'lokacija' => '📍',
            default => '📌',
        };
    }

    public function getTipLabelAttribute(): string
    {
        return match($this->tip) {
            'biljeska' => 'Bilješka',
            'opasnost' => 'Opasnost',
            'radio' => 'Radio',
            'zahtjev' => 'Zahtjev',
            'akcija' => 'Akcija',
            'lokacija' => 'Lokacija',
            default => 'Napomena',
        };
    }

    public function getTipBojaAttribute(): array
    {
        return match($this->tip) {
            'opasnost' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#DC2626'],
            'radio' => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'border' => '#3B82F6'],
            'zahtjev' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'border' => '#F59E0B'],
            'akcija' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'border' => '#10B981'],
            'lokacija' => ['bg' => '#E0E7FF', 'text' => '#3730A3', 'border' => '#6366F1'],
            default => ['bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#94A3B8'],
        };
    }
}