<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * EN: Validation rules for capturing a new Pokémon.
 * PT: Regras de validação para capturar um novo Pokémon.
 */
class StoreCapturedRequest extends FormRequest
{
    /**
     * EN: Any authenticated user may capture. / PT: Qualquer usuário autenticado pode capturar.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'pokemon_id' => ['required', 'integer', 'min:1'],
            'nickname' => ['nullable', 'string', 'max:50'],
            'level' => ['nullable', 'string', 'max:50'],
            'detail_note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
