<?php

namespace App\Filament\Admin\Resources\RepairResource\Pages;

use App\Filament\Admin\Resources\RepairResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepair extends EditRecord
{
    protected static string $resource = RepairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
