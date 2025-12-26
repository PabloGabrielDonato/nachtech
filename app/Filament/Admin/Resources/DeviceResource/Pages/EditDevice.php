<?php

namespace App\Filament\Admin\Resources\DeviceResource\Pages;

use App\Filament\Admin\Resources\DeviceResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDevice extends EditRecord
{
    protected static string $resource = DeviceResource::class;

protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('printReceipt')
                ->label('Imprimir Recibo')
                ->icon('heroicon-o-printer')
                ->color('warning')
                ->action(function ($record) {
                    $pdf = Pdf::loadView('pdf.receipt', ['device' => $record]);
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, "Recibo-{$record->id}.pdf");
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
