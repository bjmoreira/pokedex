<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * EN: A Pokémon mirrored locally from PokeAPI (data + extra details + evolution).
 * PT: Um Pokémon espelhado localmente da PokeAPI (dados + detalhes extras + evolução).
 *
 * @property int $id
 * @property string $name
 * @property array $types
 * @property string|null $image
 * @property array|null $stats
 * @property array|null $abilities
 * @property string|null $region
 * @property array|null $evolution
 */
class Pokemon extends Model
{
    protected $table = 'pokemons';

    // EN: The id comes from PokeAPI, so it is not auto-incremented.
    // PT: O id vem da PokeAPI, então não é auto-incrementado.
    public $incrementing = false;

    protected $keyType = 'int';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'types',
        'image',
        'height',
        'weight',
        'base_experience',
        'stats',
        'abilities',
        'generation',
        'region',
        'habitat',
        'color',
        'description',
        'evolution',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'types' => 'array',
            'stats' => 'array',
            'abilities' => 'array',
            'evolution' => 'array',
            'height' => 'integer',
            'weight' => 'integer',
            'base_experience' => 'integer',
        ];
    }
}
