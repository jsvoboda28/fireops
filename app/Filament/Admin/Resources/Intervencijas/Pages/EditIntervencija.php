<?php

namespace App\Filament\Admin\Resources\Intervencijas\Pages;

use App\Filament\Admin\Resources\Intervencijas\IntervencijaResource;
use App\Models\Dojava;
use App\Models\Postrojba;
use App\Models\Tim;
use App\Models\TimClanstvo;
use App\Models\TimStatusLog;
use App\Models\TimVozilo;
use App\Models\Vatrogasac;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditIntervencija extends EditRecord
{
    protected static string $resource = IntervencijaResource::class;

    protected string $view = 'filament.admin.resources.intervencijas.pages.edit-intervencija';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('noviTim')
                ->label('➕ Novi tim')
                ->color('success')
                ->button()
                ->modalHeading('Formiraj novi tim na ovoj intervenciji')
                ->modalSubmitActionLabel('Formiraj tim')
                ->schema([
                    TextInput::make('naziv')
                        ->label('Naziv tima')
                        ->required()
                        ->placeholder('npr. Tim Kaptol-1, Tim Sjever')
                        ->maxLength(100),
                    
                    Select::make('baza_postrojba_id')
                        ->label('Bazna postrojba')
                        ->options(Postrojba::where('aktivna', true)->orderBy('naziv')->pluck('naziv', 'id'))
                        ->searchable()
                        ->required(),
                    
                    Select::make('zapovjednik_id')
                        ->label('Zapovjednik tima')
                        ->options(function () {
                            return Vatrogasac::where('status', 'aktivan')
                                ->where('operativan', true)
                                ->with('postrojba')
                                ->orderBy('prezime')
                                ->get()
                                ->mapWithKeys(fn ($v) => [
                                    $v->id => $v->prezime . ' ' . $v->ime . ' (' . ($v->postrojba?->naziv ?? '?') . ')'
                                ])
                                ->toArray();
                        })
                        ->searchable()
                        ->required(),
                    
                    Textarea::make('zadatak')
                        ->label('Zadatak tima')
                        ->rows(2),
                ])
                ->action(function (array $data) {
                    $tim = Tim::create([
                        'naziv' => $data['naziv'],
                        'intervencija_id' => $this->record->id,
                        'baza_postrojba_id' => $data['baza_postrojba_id'],
                        'zapovjednik_id' => $data['zapovjednik_id'],
                        'trenutni_status' => 'formiran',
                        'zadatak' => $data['zadatak'] ?? null,
                        'vrijeme_formiranja' => now(),
                    ]);
                    
                    TimClanstvo::create([
                        'tim_id' => $tim->id,
                        'vatrogasac_id' => $data['zapovjednik_id'],
                        'uloga' => 'zapovjednik',
                        'usao_u' => now(),
                    ]);
                    
                    TimStatusLog::create([
                        'tim_id' => $tim->id,
                        'status' => 'formiran',
                        'vrijeme' => now(),
                        'autor_id' => auth()->id(),
                        'intervencija_id' => $this->record->id,
                    ]);
                    
                    Notification::make()
                        ->title('Tim formiran')
                        ->body("Tim '{$tim->naziv}' je kreiran sa zapovjednikom.")
                        ->success()
                        ->send();
                }),

            Action::make('zatvoriIntervenciju')
                ->label('🔒 Zatvori intervenciju')
                ->color('gray')
                ->visible(fn () => $this->record->status === 'aktivna')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'status' => 'zatvorena',
                        'vrijeme_zatvaranja' => now(),
                    ]);

                    Notification::make()
                        ->title('Intervencija zatvorena')
                        ->success()
                        ->send();
                }),
            
            DeleteAction::make(),
        ];
    }

    public function timPolazak(int $timId): void
    {
        $this->promijeniStatusTima($timId, 'polazak', 'Tim krenuo na intervenciju');
    }

    public function timNaMjestu(int $timId): void
    {
        $this->promijeniStatusTima($timId, 'na_mjestu', 'Tim stigao na mjesto');
    }

    public function timZavrsili(int $timId): void
    {
        $this->promijeniStatusTima($timId, 'intervencija_zavrsena', 'Tim završio intervenciju');
    }

    public function timPovratak(int $timId): void
    {
        $this->promijeniStatusTima($timId, 'povratak', 'Tim se vraća');
    }

    protected function promijeniStatusTima(int $timId, string $noviStatus, string $opis): void
    {
        $tim = Tim::find($timId);
        if (!$tim) return;

        $stari = $tim->trenutni_status;
        $tim->update(['trenutni_status' => $noviStatus]);

        TimStatusLog::create([
            'tim_id' => $tim->id,
            'status' => $noviStatus,
            'vrijeme' => now(),
            'autor_id' => auth()->id(),
            'intervencija_id' => $this->record->id,
            'napomena' => $opis,
        ]);

        Notification::make()
            ->title($tim->naziv . ': ' . $stari . ' → ' . $noviStatus)
            ->success()
            ->send();
    }

    // ===== COMPUTED METODE ZA BLADE =====

    public function getTimoviProperty()
    {
        return Tim::where('intervencija_id', $this->record->id)
            ->with([
                'zapovjednik.postrojba',
                'bazaPostrojba',
                'trenutniClanovi.vatrogasac.postrojba',
                'trenutnaVozila.vozilo.postrojba',
            ])
            ->orderByRaw("CASE trenutni_status
                WHEN 'na_mjestu' THEN 1
                WHEN 'polazak' THEN 2
                WHEN 'povratak' THEN 3
                WHEN 'formiran' THEN 4
                WHEN 'intervencija_zavrsena' THEN 5
                WHEN 'odmor' THEN 6
                WHEN 'cekanje_u_bazi' THEN 7
                WHEN 'raspusten' THEN 8
                ELSE 9
            END")
            ->orderBy('vrijeme_formiranja')
            ->get();
    }

    public function getTimelineProperty()
    {
        return TimStatusLog::where('intervencija_id', $this->record->id)
            ->with(['tim', 'autor'])
            ->orderBy('vrijeme', 'desc')
            ->limit(100)
            ->get();
    }

    public function getDojaveVezaneProperty()
    {
        return Dojava::where('intervencija_id', $this->record->id)
            ->orderBy('vrijeme_zaprimanja', 'desc')
            ->get();
    }

    public function getBrojClanovaUkupnoProperty(): int
    {
        $timIds = Tim::where('intervencija_id', $this->record->id)->pluck('id');
        return TimClanstvo::whereIn('tim_id', $timIds)
            ->whereNull('izasao_u')
            ->count();
    }

    public function getBrojVozilaUkupnoProperty(): int
    {
        $timIds = Tim::where('intervencija_id', $this->record->id)->pluck('id');
        return TimVozilo::whereIn('tim_id', $timIds)
            ->whereNull('skinuto_u')
            ->count();
    }

    public function getBrojAktivnihTimovaProperty(): int
    {
        return Tim::where('intervencija_id', $this->record->id)
            ->where('trenutni_status', '!=', 'raspusten')
            ->count();
    }
}