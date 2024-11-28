<?php

namespace App\Filament\Resources\AdTemplatesResource\Pages;

use App\Filament\Resources\AdTemplatesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdTemplates extends EditRecord
{
    protected static string $resource = AdTemplatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
