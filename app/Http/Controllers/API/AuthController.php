<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials']
            ]);
        }

        $request->session()->regenerate();

        // Создаём токен для API (если нужен для внешних запросов)
        $token = Auth::user()->createToken('api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => Auth::user()->only(['id', 'name', 'email']),
            'redirect' => '/admin/products',
            'csrf_token' => csrf_token()
        ]);
    }

    public function logout(Request $request)
    {
        // Проверяем, есть ли у пользователя токен API (для API запросов)
        // Если это web-запрос (выход из админки), токена не будет
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        // Всегда выходим из сессии Laravel (работает для web запросов)
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Очищаем токен из localStorage на фронтенде
        return response()->json([
            'message' => 'Logged out successfully',
            'redirect' => '/',
            'clear_token' => true,
            'csrf_token' => csrf_token()  // Флаг для фронтенда
        ]);
    }
}
