<?php

namespace App\Http\Controllers;

use App\Mail\AccountCreatedMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private function sendCode(User $user, string $type): bool
    {
        $code = (string) random_int(100000, 999999);

        if ($type === 'otp') {
            $user->forceFill([
                'otp_code' => Hash::make($code),
                'otp_expires_at' => now()->addMinutes(10),
            ])->save();
            $subject = 'Code de verification Fadj-Ma';
            $texte = "Bonjour {$user->name},\n\nVotre code de verification Fadj-Ma : {$code}\nIl expire dans 10 minutes.";
        } else {
            $user->forceFill([
                'reset_code' => Hash::make($code),
                'reset_expires_at' => now()->addMinutes(15),
            ])->save();
            $subject = 'Reinitialisation du mot de passe Fadj-Ma';
            $texte = "Bonjour {$user->name},\n\nVotre code de reinitialisation : {$code}\nIl expire dans 15 minutes.\nSi vous n'etes pas a l'origine de cette demande, ignorez ce message.";
        }

        try {
            Mail::raw($texte, fn ($m) => $m->to($user->email)->subject($subject));
            return true;
        } catch (\Throwable $e) {
            Log::error('Envoi email echoue: ' . $e->getMessage());
            return false;
        }
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'genre' => 'nullable|string',
            'date_naissance' => 'nullable|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if ($user && $user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => ['Cet email est deja utilise'],
            ]);
        }

        if (!$user) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make(Str::random(32)),
                'genre' => $data['genre'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
            ]);
        }

        $sent = $this->sendCode($user, 'otp');

        return response()->json([
            'message' => $sent
                ? 'Code de verification envoye par email'
                : "Compte cree mais l'email n'a pas pu etre envoye. Utilisez 'Renvoyer le code'.",
            'email_sent' => $sent,
        ], 201);
    }

    public function resendOtp(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);
        $user = User::where('email', $data['email'])->first();

        if ($user && !$user->email_verified_at) {
            $this->sendCode($user, 'otp');
        }

        return response()->json(['message' => 'Si le compte existe, un nouveau code a ete envoye']);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (
            !$user || !$user->otp_code || !$user->otp_expires_at
            || now()->gt(Carbon::parse($user->otp_expires_at))
            || !Hash::check($data['code'], $user->otp_code)
        ) {
            throw ValidationException::withMessages([
                'code' => ['Code invalide ou expire'],
            ]);
        }

        $password = Str::random(10);

        $user->forceFill([
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        try {
            Mail::to($user->email)->send(new AccountCreatedMail($user, $password));
        } catch (\Throwable $e) {
            Log::error('Envoi email bienvenue echoue: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Compte valide, mot de passe temporaire envoye par email']);
    }

    public function forgotPassword(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);
        $user = User::where('email', $data['email'])->first();

        if ($user && $user->email_verified_at) {
            $this->sendCode($user, 'reset');
        }

        return response()->json(['message' => 'Si le compte existe, un code a ete envoye par email']);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (
            !$user || !$user->reset_code || !$user->reset_expires_at
            || now()->gt(Carbon::parse($user->reset_expires_at))
            || !Hash::check($data['code'], $user->reset_code)
        ) {
            throw ValidationException::withMessages([
                'code' => ['Code invalide ou expire'],
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
            'reset_code' => null,
            'reset_expires_at' => null,
        ])->save();

        $user->tokens()->delete();

        return response()->json(['message' => 'Mot de passe modifie']);
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

        if (!$user->email_verified_at) {
            return response()->json([
                'message' => 'Compte non valide. Entrez le code recu par email.',
                'needs_verification' => true,
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}