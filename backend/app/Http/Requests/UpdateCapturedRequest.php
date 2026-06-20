<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * EN: Validation rules for updating a captured Pokémon's trainer metadata.
 * PT: Regras de validação para atualizar os metadados de treinador de um capturado.
 */
class UpdateCapturedRequest extends FormRequest
{
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
            'nickname' => ['nullable', 'string', 'max:50'],
            'level' => ['nullable', 'string', 'max:50'],
            'detail_note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
