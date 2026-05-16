<?php

namespace App\Filament\Admin\Resources\Dojavas\Pages;

use App\Filament\Admin\Resources\Dojavas\DojavaResource;
use App\Models\Intervencija;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDojava extends EditRecord
{
    protected static string $resource = DojavaResource::class;

    protected function getHeaderActions(): array
    {
        $dojava = $this->record;
        $imaIntervenciju = !is_null($dojava->intervencija_id);

        return [
            // ===== OTVORI NOVU INTERVENCIJU =====
            Action::make('otvoriIntervenciju')
                ->label('🔥 Otvori intervenciju')
                ->color('danger')
                ->button()
                ->visible(fn () => !$imaIntervenciju)
                ->modalHeading('Otvori intervenciju iz ove dojave')
                ->modalDescription('Kreira se nova intervencija s podacima iz dojave. Bit ćeš preusmjeren na Vođenje intervencije.')
                ->modalSubmitActionLabel('Otvori intervenciju')
                ->schema([
                    TextInput::make('naziv')
                        ->label('Naziv intervencije')
                        ->required()
                        ->default(fn () => $this->predloziNaziv())
                        ->maxLength(300),

                    Select::make('tip_intervencije')
                        ->label('Tip intervencije')
                        ->required()
                        ->default(fn () => $this->mapirajTip($dojava->tip_nepogode))
                        ->options([
                            'pozar' => '🔥 Požar',
                            'poplava' => '🌊 Poplava',
                            'tehnicka' => '🔧 Tehnička intervencija',
                            'prometna' => '🚗 Prometna nesreća',
                            'spasavanje' => '⛑ Spašavanje',
                            'opasne_tvari' => '☣ Opasne tvari',
                            'ostalo' => '❓ Ostalo',
                        ]),
                ])
                ->action(function (array $data) use ($dojava) {
                    // Generiraj broj intervencije
                    $godina = now()->format('Y');
                    $zadnja = Intervencija::where('broj', 'LIKE', $godina . '-INT-%')
                        ->orderByRaw("CAST(SPLIT_PART(broj, '-', 3) AS INTEGER) DESC")
                        ->first();
                    $sljedeci = 1;
                    if ($zadnja) {
                        $parts = explode('-', $zadnja->broj);
                        $sljedeci = (int) end($parts) + 1;
                    }

                    // Kreiraj intervenciju
                    $intervencija = Intervencija::create([
                        'broj' => $godina . '-INT-' . str_pad($sljedeci, 6, '0', STR_PAD_LEFT),
                        'naziv' => $data['naziv'],
                        'opis' => $dojava->opis,
                        'adresa' => $dojava->adresa,
                        'jls_id' => $dojava->jls_id,
                        'latitude' => $dojava->latitude,
                        'longitude' => $dojava->longitude,
                        'tip_intervencije' => $data['tip_intervencije'],
                        'prioritet' => $dojava->prioritet,
                        'status' => 'aktivna',
                        'voditelj_id' => auth()->id(),
                        'pocetna_dojava_id' => $dojava->id,
                        'vrijeme_otvaranja' => now(),
                    ]);

                    // Veži dojavu na intervenciju
                    $dojava->update([
                        'intervencija_id' => $intervencija->id,
                        'status' => 'dodijeljena',
                    ]);

                    Notification::make()
                        ->title('Intervencija otvorena')
                        ->body("Intervencija #{$intervencija->broj} je kreirana iz dojave #{$dojava->broj_dojave}")
                        ->success()
                        ->send();

                    // Redirect na Vođenje intervencije
                    $this->redirect(\App\Filament\Admin\Resources\Intervencijas\IntervencijaResource::getUrl('edit', ['record' => $intervencija]));
                }),

            // ===== SPOJI S POSTOJEĆOM INTERVENCIJOM =====
            Action::make('spojiSIntervencijom')
                ->label('🔗 Spoji s intervencijom')
                ->color('warning')
                ->button()
                ->visible(fn () => !$imaIntervenciju)
                ->modalHeading('Spoji dojavu s postojećom intervencijom')
                ->modalSubmitActionLabel('Spoji')
                ->schema([
                    Select::make('intervencija_id')
                        ->label('Aktivna intervencija')
                        ->options(
                            Intervencija::where('status', 'aktivna')
                                ->orderBy('vrijeme_otvaranja', 'desc')
                                ->get()
                                ->mapWithKeys(fn ($i) => [
                                    $i->id => "#{$i->broj} • {$i->naziv}"
                                ])
                                ->toArray()
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) use ($dojava) {
                    $intervencija = Intervencija::find($data['intervencija_id']);
                    if (!$intervencija) return;

                    $dojava->update([
                        'intervencija_id' => $intervencija->id,
                        'status' => 'dodijeljena',
                    ]);

                    Notification::make()
                        ->title('Dojava povezana')
                        ->body("Dojava #{$dojava->broj_dojave} je vezana na intervenciju #{$intervencija->broj}")
                        ->success()
                        ->send();

                    $this->redirect(\App\Filament\Admin\Resources\Intervencijas\IntervencijaResource::getUrl('edit', ['record' => $intervencija]));
                }),

            // ===== OTVORI INTERVENCIJU (ako već postoji) =====
            Action::make('idiNaIntervenciju')
                ->label(fn () => '🎯 Otvori intervenciju #' . ($dojava->intervencija?->broj ?? '?'))
                ->color('danger')
                ->button()
                ->visible(fn () => $imaIntervenciju)
                ->url(fn () => $dojava->intervencija_id 
                    ? \App\Filament\Admin\Resources\Intervencijas\IntervencijaResource::getUrl('edit', ['record' => $dojava->intervencija_id])
                    : null
                ),

            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Predloži naziv intervencije iz dojave.
     */
    protected function predloziNaziv(): string
    {
        $dojava = $this->record;
        $tipLabel = match($dojava->tip_nepogode) {
            'pozar' => 'Požar',
            'poplava' => 'Poplava',
            'olujno_nevrijeme' => 'Olujno nevrijeme',
            'snijeg_led' => 'Snijeg/led',
            'klizište' => 'Klizište',
            'tuca' => 'Tuča',
            'potres' => 'Potres',
            'spasavanje' => 'Spašavanje',
            'opasne_tvari' => 'Opasne tvari',
            default => 'Intervencija',
        };

        return $tipLabel . ' — ' . ($dojava->adresa ?? '?') . ', ' . ($dojava->opcina ?? $dojava->jls?->naziv ?? '?');
    }

    /**
     * Mapiraj tip nepogode na tip intervencije.
     */
    protected function mapirajTip(?string $tipNepogode): string
    {
        return match($tipNepogode) {
            'pozar' => 'pozar',
            'poplava' => 'poplava',
            'spasavanje' => 'spasavanje',
            'opasne_tvari' => 'opasne_tvari',
            'olujno_nevrijeme', 'snijeg_led', 'klizište', 'tuca' => 'tehnicka',
            'potres' => 'spasavanje',
            default => 'ostalo',
        };
    }
}