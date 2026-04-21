<?php

namespace App\Filament\Admin\Resources\EventRegistrationResource\Pages;

use App\Filament\Admin\Resources\EventRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventRegistration extends EditRecord
{
    protected static string $resource = EventRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
