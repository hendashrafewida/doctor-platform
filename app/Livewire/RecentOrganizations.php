<?php

namespace App\Livewire;

use App\Models\Organization;
use App\Services\OrganizationCreator;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\View\View;
use Livewire\Component;

class RecentOrganizations extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function createOrganizationAction(): Action
    {
        return Action::make('createOrganization')
            ->label('منظمة جديدة')
            ->icon('heroicon-o-plus')
            ->color('primary')
            ->modalHeading('إنشاء منظمة جديدة')
            ->form([
                TextInput::make('name')
                    ->label('اسم المنظمة')
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('العنوان')
                    ->required()
                    ->maxLength(255),
                TextInput::make('mobile')
                    ->label('رقم الهاتف')
                    ->required()
                    ->unique(table: 'organizations', column: 'mobile')
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required()
                    ->minLength(8),
                Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'active' => 'نشط',
                        'suspended' => 'معلق',
                    ])
                    ->default('active')
                    ->required(),
                TextInput::make('specialization')
                    ->label('التخصص')
                    ->required()
                    ->maxLength(255),
            ])
            ->action(function (array $data): void {
                app(OrganizationCreator::class)->create($data);

                Notification::make()
                    ->title('تم إنشاء المنظمة بنجاح')
                    ->success()
                    ->send();
            });
    }

    public function render(): View
    {
        return view('livewire.recent-organizations', [
            'organizations' => Organization::query()
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }
}
