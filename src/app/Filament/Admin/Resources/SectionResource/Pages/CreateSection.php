<?php

namespace App\Filament\Admin\Resources\SectionResource\Pages;

use App\Filament\Admin\Resources\SectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSection extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = SectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
