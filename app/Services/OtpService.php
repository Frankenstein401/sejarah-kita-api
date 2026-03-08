<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function send(string $email): void
    {
        // Invalidate previous unused OTPs for this email
        OtpCode::where('email', $email)->whereNull('used_at')->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'email'      => $email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Log OTP for development (replace with real mailer in production)
        Log::info("OTP for {$email}: {$code}");

        // Send email if mail is configured
        try {
            Mail::send([], [], function ($message) use ($email, $code) {
                $message->to($email)
                    ->subject('Kode Verifikasi SejarahKita')
                    ->html("
                        <div style='font-family:sans-serif;max-width:480px;margin:0 auto;padding:32px;'>
                            <h2 style='color:#e8891a;margin-bottom:8px;'>SejarahKita</h2>
                            <p style='color:#555;'>Gunakan kode berikut untuk verifikasi pendaftaran akun kamu:</p>
                            <div style='font-size:36px;font-weight:bold;letter-spacing:12px;text-align:center;padding:24px;background:#f5f0e8;border-radius:12px;margin:20px 0;color:#1a1a1a;'>{$code}</div>
                            <p style='color:#888;font-size:13px;'>Kode berlaku selama <strong>10 menit</strong>. Jangan bagikan kode ini kepada siapapun.</p>
                        </div>
                    ");
            });
        } catch (\Exception $e) {
            Log::error("Mail send failed: " . $e->getMessage());
        }
    }

    public function verify(string $email, string $code): bool
    {
        $otp = OtpCode::where('email', $email)
            ->where('code', $code)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otp || !$otp->isValid()) {
            return false;
        }

        $otp->update(['used_at' => now()]);

        return true;
    }
}
