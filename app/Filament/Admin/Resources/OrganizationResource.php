<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Actions;
use App\Models\Specialization;
use Illuminate\Support\Str;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationLabel = 'قائمة العملاء';

    protected static ?string $modelLabel = 'عميل';

    protected static ?string $pluralModelLabel = 'العملاء';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('بيانات المنظمة')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الدكتور / العيادة')
                            ->live(onBlur: false)
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                $set('slug', Str::slug($state ?? ''));
                            })
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label('معرّف المنظمة')
                            ->helperText('يُستخدم كرابط فريد ويمكن تعديله يدويًا.')
                            ->required()
                            ->unique(table: 'organizations', column: 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('city')
                            ->label('المدينة')
                            ->options(array_combine(config('cities.الغربية', []), config('cities.الغربية', [])))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('mobile')
                            ->label('رقم الهاتف')
                            ->required()
                            ->unique(table: 'organizations', column: 'mobile', ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('العنوان')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('specialization_id')
                            ->label('تخصص الدكتور')
                            ->options(Specialization::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),
                ]),
            Section::make('بيانات حساب الدخول')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                        Forms\Components\Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'active' => 'نشط',
                                'suspended' => 'معلق',
                            ])
                            ->default('active')
                            ->required(),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->label('رقم الهاتف')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialization.name')
                    ->label('التخصص')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'suspended',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'active' => 'نشط',
                        'suspended' => 'معلق',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
