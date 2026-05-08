<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * Postrojba kojoj vatrogasac pripada.
     */
    public function postrojba(): BelongsTo
    {
        return $this->belongsTo(Postrojba::class);
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
}