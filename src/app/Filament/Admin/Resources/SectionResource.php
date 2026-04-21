<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SectionResource\Pages;
use App\Filament\Admin\Resources\SectionResource\RelationManagers;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class SectionResource extends Resource
{
    use Translatable;

    protected const PAGE_OPTIONS = [
        'home' => 'Home',
        'about' => 'About',
        'events' => 'Event',
        'mitra' => 'Mitra',
        'programs' => 'Programs',
        'event' => 'Event (Legacy Alias)',
        'ourpartner' => 'Mitra (Legacy Alias)',
    ];

    protected static ?string $model = Section::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Section Info')
                ->description('Atur halaman, key section, urutan tampil, dan status aktif section.')
                ->schema([
                    Forms\Components\Select::make('page')
                        ->options(self::PAGE_OPTIONS)
                        ->placeholder('Pilih halaman')
                        ->searchable()
                        ->live()
                        ->required(),
                    Forms\Components\TextInput::make('key')
                        ->required()
                        ->placeholder(fn (Get $get): string => self::keyPlaceholder((string) $get('page')))
                        ->helperText(fn (Get $get): string => self::keyHelper((string) $get('page'))),
                    Forms\Components\TextInput::make('order')
                        ->numeric()
                        ->minValue(0)
                        ->helperText('Semakin kecil nilainya, section tampil lebih atas.')
                        ->default(0),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Content')
                ->description('Konten utama section. Semua field ini mendukung multi-language.')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->placeholder('Judul section'),
                    Forms\Components\TextInput::make('subtitle')
                        ->placeholder('Subjudul section'),
                    Forms\Components\Textarea::make('description')
                        ->rows(4)
                        ->placeholder('Deskripsi section...')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->imageEditor()
                        ->helperText('Opsional. Gunakan rasio sesuai desain halaman.')
                        ->directory('sections'),
                ])->columns(2),

            Forms\Components\Section::make('Buttons')
                ->description('Opsional. Isi jika section membutuhkan tombol aksi utama/sekunder.')
                ->schema([
                    Forms\Components\TextInput::make('button_text')
                        ->placeholder('Contoh: Pelajari Lebih Lanjut'),
                    Forms\Components\TextInput::make('button_url')
                        ->placeholder('https://... atau /path-internal'),
                    Forms\Components\TextInput::make('secondary_button_text')
                        ->placeholder('Contoh: Lihat Program'),
                    Forms\Components\TextInput::make('secondary_button_url')
                        ->placeholder('https://... atau /path-internal'),
                ])->columns(2)
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'home' => 'primary',
                        'about' => 'success',
                        'programs' => 'warning',
                        'event', 'events' => 'danger',
                        'mitra', 'ourpartner' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'events', 'event' => 'Event',
                        'mitra', 'ourpartner' => 'Mitra',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtitle')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Update')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('page')
                    ->options(self::PAGE_OPTIONS),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->groups([
                Group::make('page')
                    ->label('Halaman')
                    ->collapsible(),
            ])
            ->defaultSort('page')
            ->searchPlaceholder('Cari key atau judul section...')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }

    private static function keyPlaceholder(string $page): string
    {
        $page = strtolower(trim($page));

        return match ($page) {
            'home' => 'hero, mission, featured_programs, impact_stats, closing',
            'about' => 'hero, history, community, team, vision_mission',
            'events', 'event' => 'hero, more_events, upcoming',
            'mitra', 'ourpartner' => 'hero, international, national, cta',
            default => 'contoh: hero, mission, cta',
        };
    }

    private static function keyHelper(string $page): string
    {
        if ($page === '') {
            return 'Pilih page dulu agar rekomendasi key muncul.';
        }

        return 'Gunakan key yang konsisten agar konten otomatis tampil di komponen halaman yang sesuai.';
    }
}
