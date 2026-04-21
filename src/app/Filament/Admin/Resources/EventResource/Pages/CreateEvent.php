<?php

namespace App\Filament\Admin\Resources\EventResource\Pages;

use App\Filament\Admin\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
