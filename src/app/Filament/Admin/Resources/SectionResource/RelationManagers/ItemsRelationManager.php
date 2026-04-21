<?php

namespace App\Filament\Admin\Resources\SectionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class ItemsRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'items';
    protected static ?string $title = 'Section Items';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Content')
                ->description('Konten item yang akan tampil pada section terpilih.')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->placeholder('Judul item'),
                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->placeholder('Deskripsi item...')
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Appearance')
                ->description('Atur ikon, warna, gambar, dan tautan item.')
                ->schema([
                    Forms\Components\TextInput::make('icon')
                        ->placeholder('groups')
                        ->helperText('Material icon name (e.g., groups, dns, policy)'),
                    Forms\Components\Select::make('icon_color')
                        ->options([
                            'blue' => 'Blue',
                            'teal' => 'Teal',
                            'navy' => 'Navy',
                            'green' => 'Green',
                            'red' => 'Red',
                            'yellow' => 'Yellow',
                        ]),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->imageEditor()
                        ->directory('section-items'),
                    Forms\Components\TextInput::make('url')
                        ->placeholder('https://... atau /path-internal'),
                ])->columns(2),

            Forms\Components\Section::make('Settings')
                ->description('Tambahan metadata item dan urutan tampil.')
                ->schema([
                    Forms\Components\KeyValue::make('extra_data')
                        ->helperText('Extra data (stat_value, stat_label, etc.)')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('order')
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(55)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('icon'),
                Tables\Columns\TextColumn::make('icon_color')
                    ->badge(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->headerActions([
                Tables\Actions\LocaleSwitcher::make(),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
