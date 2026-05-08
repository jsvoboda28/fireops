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

#[Fillable(['name', 'email', 'password', 'jls_id', 'aktivan'])]
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
        // Samo aktivni korisnici mogu pristupiti
        return $this->aktivan === true;
    }

    /**
     * Veza s JLS-om (jedinica lokalne samouprave kojoj korisnik pripada).
     */
    public function jls(): BelongsTo
    {
        return $this->belongsTo(Jls::class);
    }
}