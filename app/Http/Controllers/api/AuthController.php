<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    protected $custom_validator = [
        'email.required' => 'Un email est requis.',
        'email.email' => 'L\'email n\'est pas valide.',
        'email.unique' => 'Cet email est déjà utilisé.',
        'email.max' => 'L\'email ne doit pas dépasser 100 caractères.',
        'password.required' => 'Un mot de passe est requis.',
        'password.confirmed' => 'Les 2 mot de passes sont différents.',
        'password.regex' => 'Le mot de passe doit faire 8 caractères avec au moins une majuscule et un chiffre.'
    ];

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}/'
        ], $this->custom_validator);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], $this->custom_validator);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully logged out.']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        $user = Auth::user();

        return response()->json([
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
