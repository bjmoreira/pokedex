<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * EN: Application user. Owns the captured Pokémon collection.
 * PT: Usuário da aplicação. Dono da coleção de Pokémon capturados.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * EN: Mass-assignable attributes. / PT: Atributos atribuíveis em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * EN: Attributes hidden for serialization. / PT: Atributos ocultos na serialização.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * EN: Attribute casting. / PT: Conversão de tipos dos atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * EN: The Pokémon captured by this user.
     * PT: Os Pokémon capturados por este usuário.
     *
     * @return HasMany<Captured, $this>
     */
    public function captured(): HasMany
    {
        return $this->hasMany(Captured::class);
    }
}
