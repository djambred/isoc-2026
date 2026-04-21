<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventRegistrationResource\Pages;
use App\Models\EventRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'Event Registrations';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Registration Info')
                ->schema([
                    Forms\Components\Select::make('event_id')
                        ->relationship('event', 'title')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('registration_code')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\TextInput::make('name')
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required(),
                    Forms\Components\TextInput::make('phone'),
                    Forms\Components\TextInput::make('organization'),
                    Forms\Components\TextInput::make('position'),
                    Forms\Components\Toggle::make('is_speaker')
                        ->label('Narasumber')
                        ->helperText('Aktifkan jika registran adalah narasumber/pemateri event.')
                        ->default(false),
                    Forms\Components\Textarea::make('motivation')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),
                ])->columns(2),
            Forms\Components\Section::make('Attendance & Certificate')
                ->schema([
                    Forms\Components\DateTimePicker::make('attended_at')
                        ->label('Attended At'),
                    Forms\Components\FileUpload::make('certificate_path')
                        ->label('Certificate')
                        ->disk('public')
                        ->directory('certificates')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(5120),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('registration_code')
                    ->label('Code')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->size('sm'),
                Tables\Columns\TextColumn::make('event.title')
                    ->limit(30)
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('organization')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_speaker')
                    ->label('Speaker')
                    ->boolean(),
                Tables\Columns\IconColumn::make('attended_at')
                    ->label('Attended')
                    ->boolean()
                    ->getStateUsing(fn (EventRegistration $record): bool => (bool) $record->attended_at),
                Tables\Columns\IconColumn::make('certificate_path')
                    ->label('Cert')
                    ->boolean()
                    ->getStateUsing(fn (EventRegistration $record): bool => (bool) $record->certificate_path),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (EventRegistration $record): bool => $record->status === 'pending')
                    ->action(fn (EventRegistration $record) => $record->update(['status' => 'confirmed'])),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (EventRegistration $record): bool => $record->status !== 'cancelled')
                    ->action(fn (EventRegistration $record) => $record->update(['status' => 'cancelled'])),
                Tables\Actions\Action::make('mark_attended')
                    ->label('Attended')
                    ->icon('heroicon-o-hand-raised')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (EventRegistration $record): bool => $record->status === 'confirmed' && ! $record->attended_at)
                    ->action(fn (EventRegistration $record) => $record->update(['attended_at' => now()])),
                Tables\Actions\Action::make('generate_speaker_certificate')
                    ->label('Generate Speaker Cert')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (EventRegistration $record): bool => (bool) $record->is_speaker && $record->status !== 'cancelled')
                    ->action(function (EventRegistration $record): void {
                        if (! $record->event) {
                            Notification::make()
                                ->title('Event tidak ditemukan untuk registrasi ini.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $pdf = static::makeSpeakerCertificatePdf($record);
                        $path = 'certificates/speakers/event-' . $record->event_id . '/speaker-' . $record->registration_code . '.pdf';

                        Storage::disk('public')->put($path, $pdf->output());

                        $record->update([
                            'certificate_path' => $path,
                            'attended_at' => $record->attended_at ?? now(),
                        ]);

                        Notification::make()
                            ->title('Sertifikat narasumber berhasil digenerate.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('upload_certificate')
                    ->label('Upload Cert')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('warning')
                    ->visible(fn (EventRegistration $record): bool => (bool) $record->attended_at && ! $record->certificate_path)
                    ->form([
                        Forms\Components\FileUpload::make('certificate')
                            ->label('Certificate PDF')
                            ->disk('public')
                            ->directory('certificates')
                            ->acceptedFileTypes(['application/pdf'])
                            ->required(),
                    ])
                    ->action(fn (EventRegistration $record, array $data) => $record->update(['certificate_path' => $data['certificate']])),
                Tables\Actions\Action::make('preview_certificate')
                    ->label('Preview Cert')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->visible(fn (EventRegistration $record): bool => (bool) $record->attended_at)
                    ->url(fn (EventRegistration $record): string => route('admin.certificate.preview', $record), shouldOpenInNewTab: true),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('confirm_selected')
                        ->label('Confirm Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'confirmed'])),
                    Tables\Actions\BulkAction::make('cancel_selected')
                        ->label('Cancel Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'cancelled'])),
                    Tables\Actions\BulkAction::make('mark_attended_selected')
                        ->label('Mark Attended')
                        ->icon('heroicon-o-hand-raised')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn ($r) => $r->update(['attended_at' => now()]))),
                    Tables\Actions\BulkAction::make('generate_speaker_certs')
                        ->label('Generate Speaker Certs')
                        ->icon('heroicon-o-document-text')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            $generated = 0;

                            foreach ($records as $record) {
                                if (! $record->is_speaker || $record->status === 'cancelled' || ! $record->event) {
                                    continue;
                                }

                                $pdf = static::makeSpeakerCertificatePdf($record);
                                $path = 'certificates/speakers/event-' . $record->event_id . '/speaker-' . $record->registration_code . '.pdf';

                                Storage::disk('public')->put($path, $pdf->output());

                                $record->update([
                                    'certificate_path' => $path,
                                    'attended_at' => $record->attended_at ?? now(),
                                ]);

                                $generated++;
                            }

                            Notification::make()
                                ->title($generated > 0 ? "{$generated} sertifikat narasumber berhasil digenerate." : 'Tidak ada data narasumber valid yang dipilih.')
                                ->color($generated > 0 ? 'success' : 'warning')
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function makeSpeakerCertificatePdf(EventRegistration $registration)
    {
        $registration->loadMissing('event');
        $event = $registration->event;

        $pdf = Pdf::loadView('certificates.speaker', compact('registration', 'event'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('defaultFont', 'Helvetica');
        $pdf->setOption('dpi', 150);

        return $pdf;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventRegistrations::route('/'),
            'edit' => Pages\EditEventRegistration::route('/{record}/edit'),
        ];
    }
}
