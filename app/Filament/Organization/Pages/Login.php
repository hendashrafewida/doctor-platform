<?php

namespace App\Filament\Organization\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Schema;
use App\Models\Organization;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class Login extends BaseLogin
{
    /**
     * Register routes for this page.
     */
    public static function registerRoutes(Router $router): void
    {
        // Let Filament handle auth routing - don't register custom routes
    }

    /**
     * Override the form to use 'name' instead of 'email'.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getOrganizationFormComponent(),
                $this->getNameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getOrganizationFormComponent(): Component
    {
        return Select::make('organization_id')
            ->label('اسم المنظمة')
            ->options(fn (): array => Organization::query()->orderBy('name')->pluck('name', 'id')->all())
            ->searchable()
            ->preload()
            ->required();
    }

    /**
     * Get the name form component instead of email.
     */
    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('filament-panels::auth/pages/login.form.email.label'))
            ->placeholder(__('filament-panels::auth/pages/login.form.email.placeholder'))
            ->required()
            ->autocomplete('username')
            ->autofocus();
    }

    /**
     * Get the password form component.
     */
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::auth/pages/login.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required();
    }

    /**
     * Get the remember form component.
     */
    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label(__('filament-panels::auth/pages/login.form.remember.label'));
    }

    /**
     * Override getCredentialsFromFormData to use 'name' field.
     */
    protected function getCredentialsFromFormData(#[\SensitiveParameter] array $data): array
    {
        return [
            'name' => $data['name'] ?? null,
            'organization_id' => $data['organization_id'] ?? null,
            'password' => $data['password'] ?? null,
        ];
    }

    /**
     * Override throwFailureValidationException to show error on 'name' field.
     */
    protected function throwFailureValidationException(): never
    {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'data.name' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}


