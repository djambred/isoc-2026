<?php

namespace App\Filament\Admin\Resources\PartnerResource\Pages;

use App\Filament\Admin\Resources\PartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePartner extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
