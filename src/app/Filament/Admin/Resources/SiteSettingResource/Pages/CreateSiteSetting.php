<?php

namespace App\Filament\Admin\Resources\SiteSettingResource\Pages;

use App\Filament\Admin\Resources\SiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteSetting extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
