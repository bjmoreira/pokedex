<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * EN: A Pokémon captured by a user, with trainer metadata (nickname, level, notes).
 * PT: Um Pokémon capturado por um usuário, com metadados do treinador (apelido, nível, notas).
 *
 * @property int $id
 * @property int $user_id
 * @property int $pokemon_id
 * @property string|null $nickname
 * @property string|null $level
 * @property string|null $detail_note
 */
class Captured extends Model
{
    /** @use HasFactory<\Database\Factories\CapturedFactory> */
    use HasFactory;

    /**
     * EN: Database table name. / PT: Nome da tabela no banco.
     */
    protected $table = 'captured';

    /**
     * EN: Mass-assignable attributes. / PT: Atributos atribuíveis em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'pokemon_id',
        'nickname',
        'level',
        'detail_note',
    ];

    /**
     * EN: Attribute casting. / PT: Conversão de tipos dos atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pokemon_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    /**
     * EN: Owner of this captured Pokémon. / PT: Dono deste Pokémon capturado.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
