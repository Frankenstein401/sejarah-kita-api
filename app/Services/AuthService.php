<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'status'    => $data['status'] ?? 'umum',
            'education' => $data['education'] ?? null,
            'password'  => Hash::make($data['password']),
        ]);

        $token = auth('api')->login($user);

        return $this->tokenResponse($token, $user);
    }

    public function login(array $credentials): ?array
    {
        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return null;
        }

        return $this->tokenResponse($token, auth('api')->user());
    }

    public function me(): array
    {
        $user = auth('api')->user();

        return [
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'phone'     => $user->phone,
            'status'    => $user->status,
            'education' => $user->education,
            'avatar'    => $user->avatar,
            'role'      => $user->role,
        ];
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function refresh(): array
    {
        $token = auth('api')->refresh();

        return $this->tokenResponse($token, auth('api')->user());
    }

    protected function tokenResponse(string $token, User $user): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'phone'     => $user->phone,
                'status'    => $user->status,
                'education' => $user->education,
                'role'      => $user->role,
            ],
        ];
    }
}
