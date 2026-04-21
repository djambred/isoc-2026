<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    use Translatable;

    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Event Info')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required(),
                    Forms\Components\TextInput::make('category')
                        ->helperText('e.g., Webinar, Workshop, Panel Diskusi'),
                    Forms\Components\Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Schedule & Location')
                ->schema([
                    Forms\Components\DatePicker::make('date'),
                    Forms\Components\TextInput::make('time_info')
                        ->helperText('e.g., 14:00 - 16:00 WIB'),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\Select::make('location_type')
                        ->options([
                            'online' => 'Online',
                            'offline' => 'Offline',
                            'hybrid' => 'Hybrid',
                        ])
                        ->default('offline'),
                    Forms\Components\TextInput::make('capacity_info')
                        ->helperText('e.g., 250+ Peserta'),
                    Forms\Components\TextInput::make('registration_url')
                        ->url(),
                    Forms\Components\TextInput::make('max_participants')
                        ->numeric()
                        ->helperText('Kosongkan jika tidak ada batas peserta'),
                    Forms\Components\Toggle::make('registration_open')
                        ->helperText('Buka/tutup pendaftaran internal')
                        ->default(false),
                    Forms\Components\TextInput::make('attendance_code')
                        ->label('Kode Kehadiran')
                        ->helperText('Kode rahasia untuk verifikasi kehadiran peserta di lokasi event. Umumkan saat event berlangsung.')
                        ->placeholder('Contoh: HADIR2026'),
                ])->columns(2),

            Forms\Components\Section::make('Media & Settings')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('events'),
                    Forms\Components\TextInput::make('order')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('is_featured')
                        ->default(false),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Certificate Template')
                ->description('Custom HTML template for event certificate. Leave empty to use default. Available placeholders: {{participant_name}}, {{participant_email}}, {{participant_organization}}, {{participant_position}}, {{registration_code}}, {{event_title}}, {{event_date}}, {{event_date_id}}, {{event_location}}, {{event_time}}, {{event_category}}, {{attended_at}}, {{current_year}}')
                ->schema([
                    Forms\Components\Textarea::make('certificate_template')
                        ->label('HTML Template')
                        ->rows(20)
                        ->columnSpanFull()
                        ->placeholder('Kosongkan untuk menggunakan template sertifikat default...'),
                ])
                ->collapsible()
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'success',
                        'offline' => 'warning',
                        'hybrid' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('registration_open')
                    ->boolean()
                    ->label('Reg. Open'),
                Tables\Columns\TextColumn::make('registrations_count')
                    ->counts('registrations')
                    ->label('Registrants'),
                Tables\Columns\TextColumn::make('attendance_code')
                    ->label('Kode Hadir')
                    ->badge()
                    ->color('success')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\Action::make('generate_attendance_code')
                    ->label('Generate Kode Hadir')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Kode Kehadiran')
                    ->modalDescription('Generate kode kehadiran untuk event ini. Kode ini akan diumumkan saat event berlangsung.')
                    ->modalSubmitActionLabel('Generate')
                    ->action(function (Event $record) {
                        $date = $record->date ? $record->date->format('dmY') : now()->format('dmY');
                        $code = 'ISOC' . $date;
                        $record->update(['attendance_code' => $code]);

                        Notification::make()
                            ->title('Kode Kehadiran Berhasil Di-generate')
                            ->body('Kode: ' . $code)
                            ->success()
                            ->persistent()
                            ->send();
                    })
                    ->visible(fn (Event $record) => $record->is_active && ! $record->attendance_code),
                Tables\Actions\Action::make('view_attendance_code')
                    ->label(fn (Event $record) => $record->attendance_code)
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Kode Kehadiran')
                    ->modalDescription(fn (Event $record) => 'Kode kehadiran untuk event ini:')
                    ->modalContent(fn (Event $record) => view('filament.actions.view-attendance-code', ['code' => $record->attendance_code]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->extraAttributes(fn (Event $record) => [
                        'title' => 'Kode: ' . $record->attendance_code,
                    ])
                    ->visible(fn (Event $record) => $record->is_active && $record->attendance_code),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
