<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    public bool $showFallback = false;

    public function mount(): void
    {
        parent::mount();

        if (request()->query('fallback') === '1' || request()->query('fallback') === 'true') {
            $this->showFallback = true;
        }
    }

    public function toggleFallback(): void
    {
        $this->showFallback = ! $this->showFallback;
    }

    public function getHeading(): string|Htmlable
    {
        $appName = config('app.name');
        if (empty($appName) || $appName === 'Laravel') {
            $appName = 'Microsite BPS Demak';
        }

        return 'Masuk ke '.$appName;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Sistem Informasi Microsite & Shortlink BPS Kab. Demak';
    }
}
