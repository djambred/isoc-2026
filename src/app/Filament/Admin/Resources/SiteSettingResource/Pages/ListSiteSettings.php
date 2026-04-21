<?php

namespace App\Filament\Admin\Resources\SiteSettingResource\Pages;

use App\Filament\Admin\Resources\SiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteSettings extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
