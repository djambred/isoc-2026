<?php

namespace App\Filament\Admin\Resources\SectionResource\Pages;

use App\Filament\Admin\Resources\SectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSection extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = SectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
