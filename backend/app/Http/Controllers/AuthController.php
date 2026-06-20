<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * EN: Token-based authentication (Laravel Sanctum personal access tokens).
 * PT: Autenticação por token (tokens de acesso pessoal do Laravel Sanctum).
 */
class AuthController extends Controller
{
    /**
     * EN: Validate credentials and issue an API token.
     * PT: Valida as credenciais e emite um token de API.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            // EN: Generic error to avoid leaking which field was wrong.
            // PT: Erro genérico para não revelar qual campo estava incorreto.
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user = $request->user() ?? Auth::user();

        // EN: Fresh token per login keeps sessions independent.
        // PT: Um token novo por login mantém as sessões independentes.
        $token = $user->createToken('spa')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * EN: Return the currently authenticated user.
     * PT: Retorna o usuário autenticado atual.
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user()->only(['id', 'name', 'email']));
    }

    /**
     * EN: Revoke the token used in the current request.
     * PT: Revoga o token usado na requisição atual.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => __('Logged out.')]);
    }
}
