<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected OtpService $otpService,
    ) {}

    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $this->otpService->send($request->email);

        return response()->json(['message' => 'Kode OTP telah dikirim ke email kamu.']);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string|size:6',
        ]);

        $valid = $this->otpService->verify($request->email, $request->code);

        if (!$valid) {
            return response()->json(['message' => 'Kode OTP tidak valid atau sudah kedaluwarsa.'], 422);
        }

        return response()->json(['message' => 'OTP terverifikasi.', 'verified' => true]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'phone'     => 'nullable|string|max:20',
            'status'    => 'required|in:pelajar,guru,umum',
            'education' => 'nullable|string|max:255',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        $result = $this->authService->register($request->only(
            'name', 'email', 'phone', 'status', 'education', 'password'
        ));

        return response()->json($result, 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($request->only('email', 'password'));

        if (!$result) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        return response()->json($result);
    }

    public function me(): JsonResponse
    {
        return response()->json(['data' => $this->authService->me()]);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => 'Berhasil logout.']);
    }

    public function refresh(): JsonResponse
    {
        return response()->json($this->authService->refresh());
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama tidak sesuai.'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    public function deleteAccount(): JsonResponse
    {
        $user = auth()->user();
        auth()->logout();
        $user->delete();

        return response()->json(['message' => 'Akun berhasil dihapus.']);
    }
}
