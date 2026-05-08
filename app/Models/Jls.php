<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jls extends Model
{
    protected $table = 'jls';
    
    protected $fillable = [
        'naziv',
        'tip',
        'zupanija',
        'mb',
        'oib',
        'adresa',
        'telefon',
        'email',
        'web',
        'aktivan',
        'napomena',
    ];
    
    protected $casts = [
        'aktivan' => 'boolean',
    ];
}