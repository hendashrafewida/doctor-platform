<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class DashboardLoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        return Filament::getCurrentPanel()?->getId() === 'organization'
            ? redirect()->to(Filament::getCurrentPanel()->getUrl())
            : redirect()->route('dashboard');
    }
}