<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'jls_id', 'vz_id', 'aktivan'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aktivan' => 'boolean',
        ];
    }

    /**
     * Određuje tko ima pristup admin panelu.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->aktivan === true;
    }

    /**
     * Veza s JLS-om (jedinica lokalne samouprave kojoj korisnik pripada).
     */
    public function jls(): BelongsTo
    {
        return $this->belongsTo(Jls::class);
    }

    /**
     * Vatrogasna zajednica kojoj korisnik pripada (npr. VZP Pakrac-Lipik).
     */
    public function vz(): BelongsTo
    {
        return $this->belongsTo(VatrogasnaZajednica::class);
    }

    // ====================================================================
    // METODE ZA PROVJERU PRISTUPA (SCOPE)
    // ====================================================================

    /**
     * Da li korisnik može vidjeti sve dojave/događaje (županijska razina)?
     */
    public function vidiSve(): bool
    {
        return $this->hasAnyRole([
            'super_admin',
            'zupanijski_zapovjednik',
            'zupanijski_dispecer',
            'operater_112',
        ]);
    }

    /**
     * Vraća popis ID-ova JLS-ova koje korisnik smije vidjeti.
     * Prazan array = korisnik vidi sve (županijska razina ili admin).
     */
    public function vidljiviJlsIdovi(): array
    {
        // Županijska razina i admin vide sve
        if ($this->vidiSve()) {
            return [];
        }

        // Područni zapovjednik / dispečer — vidi sve JLS-ove svoje VZ
        if ($this->vz_id && $this->vz) {
            return $this->vz->svojJlsIdove();
        }

        // Općinski zapovjednik / operater — vidi samo svoj JLS
        if ($this->jls_id) {
            return [$this->jls_id];
        }

        return [];
    }
}