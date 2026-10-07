<?php

namespace App\Http\Controllers;

use App\Mail\AccountCreatedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'genre' => 'nullable|string',
            'date_naissance' => 'nullable|string',
        ]);

        $randomPassword = Str::random(10);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($randomPassword),
            'genre' => $data['genre'] ?? null,
            'date_naissance' => $data['date_naissance'] ?? null,
        ]);

        try {
            Mail::to($user->email)->send(new AccountCreatedMail($user, $randomPassword));
        } catch (\Throwable $e) {
            Log::error('Envoi email echoue: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Compte cree, identifiants envoyes par email'], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email ou mot de passe incorrect'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}