<?php

namespace App\Filament\Admin\Resources\TeamMemberResource\Pages;

use App\Filament\Admin\Resources\TeamMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeamMembers extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
