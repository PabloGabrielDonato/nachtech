<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DeviceResource\Pages;
use App\Filament\Admin\Resources\DeviceResource\RelationManagers;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del Equipo')->schema([
                Forms\Components\TextInput::make('model')->required(),
                Forms\Components\TextInput::make('imei')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('status')
                    ->options([
                        'stock' => 'En Stock (Venta)',
                        'repair' => 'En Reparación',
                        'pickup_ready' => 'Listo para Retirar',
                        'sold' => 'Vendido / Entregado',
                    ])->required(),
                Forms\Components\DatePicker::make('entry_date')->default(now()),
            ])->columns(2),

            Forms\Components\Section::make('Finanzas y Cliente')->schema([
                Forms\Components\TextInput::make('purchase_price')->numeric()->prefix('$')->label('Costo Compra'),
                Forms\Components\TextInput::make('sale_price')->numeric()->prefix('$')->label('Precio Venta'),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('phone')->required(),
                    ])->label('Cliente'),
            ])->columns(3),

            Forms\Components\Textarea::make('condition_notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'stock' => 'info',
                        'repair' => 'warning',
                        'pickup_ready' => 'success',
                        'sold' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('customer.name')->label('Cliente'),
                Tables\Columns\TextColumn::make('profit')->label('Ganancia')->money('USD')->state(fn($record) => $record->profit),
            ])
            ->actions([
                // 1. AGREGAMOS EL BOTÓN PARA VER LA INFOLIST
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),

                // 2. BOTÓN WHATSAPP
                Tables\Actions\Action::make('whatsapp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn($record) => $record->customer ? "https://wa.me/{$record->customer->phone}?text=" . urlencode("Hola {$record->customer->name}, tu {$record->model} está listo.") : null)
                    ->openUrlInNewTab()
                // He comentado esta linea para que SIEMPRE veas el boton mientras pruebas
                // Descoméntala cuando quieras que solo salga en equipos listos
                // ->visible(fn ($record) => $record->status === 'pickup_ready'), 
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Ficha Técnica')->schema([
                TextEntry::make('model'),
                TextEntry::make('imei'),
                TextEntry::make('status')->badge(),
            ])->columns(3),
            Section::make('Historial')->schema([
                RepeatableEntry::make('repairs')->schema([
                    TextEntry::make('description'),
                    TextEntry::make('cost')->money('USD'),
                ])
            ])
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
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
        ];
    }
}
