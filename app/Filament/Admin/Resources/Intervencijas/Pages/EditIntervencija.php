<?php

namespace App\Filament\Admin\Resources\Intervencijas\Pages;

use App\Filament\Admin\Resources\Intervencijas\IntervencijaResource;
use App\Models\Postrojba;
use App\Models\Tim;
use App\Models\TimClanstvo;
use App\Models\TimStatusLog;
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
            // Otvori novi tim na ovoj intervenciji
            Action::make('noviTim')
                ->label('➕ Novi tim')
                ->color('success')
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
                        ->required()
                        ->helperText('Postrojba odakle tim dolazi'),
                    
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
                        ->rows(2)
                        ->placeholder('npr. Gašenje glavnog požara'),
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
                    
                    // Dodaj zapovjednika kao prvog člana
                    TimClanstvo::create([
                        'tim_id' => $tim->id,
                        'vatrogasac_id' => $data['zapovjednik_id'],
                        'uloga' => 'zapovjednik',
                        'usao_u' => now(),
                    ]);
                    
                    // Status log
                    TimStatusLog::create([
                        'tim_id' => $tim->id,
                        'status' => 'formiran',
                        'vrijeme' => now(),
                        'autor_id' => auth()->id(),
                        'intervencija_id' => $this->record->id,
                    ]);
                    
                    Notification::make()
                        ->title('Tim formiran')
                        ->body("Tim '{$tim->naziv}' je kreiran. Sada možeš dodati vatrogasce i postaviti status.")
                        ->success()
                        ->send();
                }),
            
            DeleteAction::make(),
        ];
    }

    public function getViewData(): array
    {
        $intervencija = $this->record;
        
        $timovi = Tim::where('intervencija_id', $intervencija->id)
            ->with([
                'zapovjednik.postrojba',
                'bazaPostrojba',
                'trenutniClanovi.vatrogasac.postrojba',
                'trenutnaVozila.vozilo',
            ])
            ->orderBy('vrijeme_formiranja')
            ->get();

        $timeline = TimStatusLog::where('intervencija_id', $intervencija->id)
            ->with(['tim', 'autor'])
            ->orderBy('vrijeme', 'desc')
            ->limit(50)
            ->get();

        return [
            'intervencija' => $intervencija,
            'timovi' => $timovi,
            'timeline' => $timeline,
        ];
    }
}