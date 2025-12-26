<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RepairResource\Pages;
use App\Filament\Admin\Resources\RepairResource\RelationManagers;
use App\Models\Repair;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RepairResource extends Resource
{
    protected static ?string $model = Repair::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('device_id')
                ->relationship('device', 'model')
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->model} - {$record->imei}")
                ->searchable()->required(),
            Forms\Components\TextInput::make('description')->required(),
            Forms\Components\TextInput::make('cost')->numeric()->prefix('$')->label('Costo Repuesto'),
            Forms\Components\TextInput::make('price_charged')->numeric()->prefix('$')->label('Cobrado al Cliente'),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_charged')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListRepairs::route('/'),
            'create' => Pages\CreateRepair::route('/create'),
            'edit' => Pages\EditRepair::route('/{record}/edit'),
        ];
    }
}
