<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimStatusLog extends Model
{
    use HasFactory;

    protected $table = 'tim_status_log';

    protected $fillable = [
        'tim_id',
        'status',
        'vrijeme',
        'autor_id',
        'intervencija_id',
        'napomena',
    ];

    protected $casts = [
        'vrijeme' => 'datetime',
    ];

    public function tim(): BelongsTo
    {
        return $this->belongsTo(Tim::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function intervencija(): BelongsTo
    {
        return $this->belongsTo(Intervencija::class);
    }
}