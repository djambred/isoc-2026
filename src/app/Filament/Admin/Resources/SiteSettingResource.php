<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    use Translatable;

    protected static ?string $model = SiteSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\Select::make('group')
                ->options([
                    'general' => 'General',
                    'footer' => 'Footer',
                    'social' => 'Social Media',
                    'seo' => 'SEO',
                ])
                ->default('general'),
            Forms\Components\Textarea::make('value')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(60),
                Tables\Columns\TextColumn::make('group')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'footer' => 'Footer',
                        'social' => 'Social Media',
                        'seo' => 'SEO',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
