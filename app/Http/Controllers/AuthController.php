<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            unset($user->email_verified_at);
            unset($user->created_at);
            unset($user->updated_at);
            unset($user->deleted_at);

            $user->tokens()->delete();
            $token = $user->createToken("create token")->plainTextToken;
            $user->token = $token;

            return response(["data" => $user]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return response(['message' => 'Logout Success']);
    }

    public function me(Request $request)
    {
        return response(["data" => $request->user()]);
    }
}
