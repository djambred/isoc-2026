<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    use Translatable;

    protected static ?string $model = Partner::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Partner Info')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required(),
                    Forms\Components\TextInput::make('subtitle'),
                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Details')
                ->schema([
                    Forms\Components\FileUpload::make('logo')
                        ->image()
                        ->directory('partners'),
                    Forms\Components\TextInput::make('logo_url')
                        ->url()
                        ->helperText('External logo URL (fallback if no upload)'),
                    Forms\Components\TextInput::make('url')
                        ->url(),
                    Forms\Components\Select::make('type')
                        ->options([
                            'international' => 'International',
                            'national' => 'National',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('order')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'international' => 'primary',
                        'national' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'international' => 'International',
                        'national' => 'National',
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
