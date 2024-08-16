<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationLabel = 'Empleados';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('country_id')
                    ->label('Country')
                    ->options(Country::all()->pluck('name', 'id')->toArray())
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),

                Select::make('state_id')
                    ->label('State')
                    ->required()
                    ->options( function (callable $get){
                        $country = Country::find($get('country_id'));
                        if(!$country){
                            return State::all()->pluck('name', 'id');
                        }
                        return $country->states->pluck('name', 'id');
                    })
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                Select::make('city_id')
                    ->label('City')
                    ->options( function (callable $get){
                        $state = State::find($get('state_id'));
                        if(!$state){
                            return City::all()->pluck('name', 'id');
                        }
                        return $state->cities->pluck('name', 'id');
                    })
                    ->required()
                    ->reactive(),

                Select::make('department_id')
                    ->relationship(name: 'department', titleAttribute: 'name')
                    ->required(),
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(200),
                TextInput::make('last_name')
                    ->required()
                    ->maxLength(200),
                TextInput::make('address')
                    ->required()
                    ->maxLength(200),
                TextInput::make('phone_number')
                    ->required()
                    ->maxLength(20),
                TextInput::make('zip_code')
                    ->required()
                    ->maxLength(10),
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
                    ->label('Nombre')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('last_name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone_number')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('zip_code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('country.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('state.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('city.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('department.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('birth_date')
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('date_hired')
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault : true),
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
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
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
