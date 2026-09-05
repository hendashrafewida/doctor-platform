<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\Specialization;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationLabel = 'العملاء';
    protected static ?string $modelLabel = 'عميل';
    protected static ?string $pluralModelLabel = 'العملاء';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('بيانات العميل')->schema([
                Grid::make(2)->schema([
                    Forms\Components\Select::make('organization_id')->label('المنظمة')->options(Organization::query()->orderBy('name')->pluck('name', 'id'))->searchable()->required(),
                    Forms\Components\TextInput::make('name')->label('الاسم')->required()->maxLength(255),
                    Forms\Components\Select::make('city')->label('المدينة')->options(array_combine(config('cities.الغربية', []), config('cities.الغربية', [])))->searchable()->required(),
                    Forms\Components\TextInput::make('mobile')->label('رقم الهاتف')->required()->unique(table: 'customers', column: 'mobile', ignoreRecord: true)->maxLength(255),
                    Forms\Components\TextInput::make('address')->label('العنوان')->required()->maxLength(255),
                    Forms\Components\Select::make('specialization_id')->label('التخصص')->options(Specialization::query()->orderBy('name')->pluck('name', 'id'))->searchable()->required(),
                    Forms\Components\Select::make('status')->label('الحالة')->options(['active' => 'نشط', 'suspended' => 'معلق'])->default('active')->required(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('organization.name')->label('المنظمة')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('mobile')->label('رقم الهاتف')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('specialization.name')->label('التخصص')->searchable()->sortable(),
            Tables\Columns\BadgeColumn::make('status')->label('الحالة')->colors(['success' => 'active', 'danger' => 'suspended'])->sortable(),
            Tables\Columns\TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime('Y-m-d H:i')->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->label('الحالة')->options(['active' => 'نشط', 'suspended' => 'معلق']),
        ])->actions([
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ])->bulkActions([
            Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}