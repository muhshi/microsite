<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class SsoController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('sipetra')->redirect();
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            $errorDescription = $request->get('error_description') ?? 'Login SSO dibatalkan oleh pengguna.';

            return redirect()->route('filament.admin.auth.login')->with('error', $errorDescription);
        }

        try {
            $ssoUser = Socialite::driver('sipetra')->user();
        } catch (\Throwable $e) {
            Log::error('SIPETRA SSO Callback error: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->route('filament.admin.auth.login')->with(
                'error',
                'Gagal terhubung dengan server SIPETRA atau otentikasi tidak valid. Silakan coba kembali.'
            );
        }

        $rawData = $ssoUser->getRaw();

        // Cari user berdasarkan sipetra_id atau email
        $user = User::where('sipetra_id', $ssoUser->getId())->first()
             ?? User::where('email', $ssoUser->getEmail())->first();

        $data = [
            'sipetra_id' => $ssoUser->getId(),
            'name' => $ssoUser->getName(),
            'email' => $ssoUser->getEmail(),
            'sipetra_token' => $ssoUser->token,
            'nip' => $rawData['nip'] ?? null,
            'jabatan' => $rawData['jabatan'] ?? null,
        ];

        if ($user) {
            $user->update($data);
        } else {
            $data['password'] = null;
            $user = User::create($data);

            // Assign role default
            if (method_exists($user, 'assignRole')) {
                try {
                    Role::firstOrCreate(['name' => 'pegawai', 'guard_name' => 'web']);
                    $user->assignRole('pegawai');
                } catch (\Throwable $e) {
                    Log::warning('Gagal assign default role pegawai: '.$e->getMessage());
                }
            }
        }

        Auth::login($user);

        Notification::make()
            ->title('Profil Berhasil Disinkronkan dari SIPETRA')
            ->success()
            ->send();

        return redirect()->intended('/admin');
    }
}
