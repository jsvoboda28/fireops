<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VatrogasnaZajednica extends Model
{
    protected $table = 'vatrogasne_zajednice';
    
    protected $fillable = [
        'naziv',
        'skraceni_naziv',
        'tip',
        'roditelj_id',
        'oib',
        'adresa',
        'telefon',
        'email',
        'web',
        'aktivna',
        'napomena',
    ];
    
    protected $casts = [
        'aktivna' => 'boolean',
    ];
    
    /**
     * Roditeljska VZ (npr. VZP Požeština ima roditelja VZ PSŽ).
     */
    public function roditelj(): BelongsTo
    {
        return $this->belongsTo(VatrogasnaZajednica::class, 'roditelj_id');
    }
    
    /**
     * Djeca (npr. VZ PSŽ ima 3 djece — VZG Požega, VZP Požeština, VZP Pakrac-Lipik).
     */
    public function djeca(): HasMany
    {
        return $this->hasMany(VatrogasnaZajednica::class, 'roditelj_id');
    }
    
    /**
     * Svi JLS-ovi koji pripadaju ovoj VZ.
     */
    public function jlsovi(): BelongsToMany
    {
        return $this->belongsToMany(Jls::class, 'vz_jls', 'vz_id', 'jls_id')->withTimestamps();
    }
    
    /**
     * Svi korisnici koji pripadaju ovoj VZ.
     */
    public function korisnici(): HasMany
    {
        return $this->hasMany(User::class, 'vz_id');
    }
    
    /**
     * Vraća listu ID-ova svih JLS-ova koji pripadaju ovoj VZ
     * (uključuje JLS-ove iz djece — npr. VZ PSŽ vraća sve JLS-ove svojih područnih VZ-ova).
     */
    public function svojJlsIdove(): array
    {
        $ids = $this->jlsovi()->pluck('jls.id')->toArray();
        
        foreach ($this->djeca as $dijete) {
            $ids = array_merge($ids, $dijete->svojJlsIdove());
        }
        
        return array_unique($ids);
    }
}