<?php

namespace App\Filament\Admin\Resources\PartnerResource\Pages;

use App\Filament\Admin\Resources\PartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartners extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
