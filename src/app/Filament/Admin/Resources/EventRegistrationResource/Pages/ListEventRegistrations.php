<?php

namespace App\Filament\Admin\Resources\EventRegistrationResource\Pages;

use App\Filament\Admin\Resources\EventRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventRegistrations extends ListRecords
{
    protected static string $resource = EventRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
