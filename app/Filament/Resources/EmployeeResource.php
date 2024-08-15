<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->required(),
                Select::make('state_id')
                    ->relationship(name: 'state', titleAttribute: 'name')
                    ->required(),
                Select::make('city_id')
                    ->relationship(name: 'city', titleAttribute: 'name')
                    ->required(),
                Select::make('department_id')
                    ->relationship(name: 'department', titleAttribute: 'name')
                    ->required(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('phone_number')
                    ->required(),
                TextInput::make('zip_code')
                    ->required(),
                DatePicker::make('birth_date')
                    ->required(),
                DatePicker::make('date_hired')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('zip_code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('country.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('state.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('birth_date')
                    ->sortable()
                    ->date(),
                TextColumn::make('date_hired')
                    ->sortable()
                    ->date(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                //
                SelectFilter::make('department')
                    ->relationship('department', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
