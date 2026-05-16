<?php

namespace App\Filament\Admin\Resources\Tims\Pages;

use App\Filament\Admin\Resources\Tims\TimResource;
use App\Models\TimClanstvo;
use App\Models\TimStatusLog;
use App\Models\Vatrogasac;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTim extends EditRecord
{
    protected static string $resource = TimResource::class;

    protected string $view = 'filament.admin.resources.tims.pages.edit-tim';

    protected function getHeaderActions(): array
    {
        return [
            // ===== DODAJ VATROGASCA U TIM =====
            Action::make('dodajVatrogasca')
                ->label('➕ Dodaj vatrogasca')
                ->color('success')
                ->modalHeading('Dodaj vatrogasca u tim')
                ->modalDescription('Pretraži po imenu — možeš odabrati iz BILO KOJE postrojbe PSŽ.')
                ->modalSubmitActionLabel('Dodaj u tim')
                ->schema([
                    Select::make('vatrogasac_id')
                        ->label('Vatrogasac')
                        ->options(function () {
                            // ID-ovi vatrogasaca koji su VEĆ u ovom timu (da se ne mogu dvostruko dodati)
                            $vecUTimu = TimClanstvo::where('tim_id', $this->record->id)
                                ->whereNull('izasao_u')
                                ->pluck('vatrogasac_id')
                                ->toArray();

                            return Vatrogasac::where('status', 'aktivan')
                                ->where('operativan', true)
                                ->whereNotIn('id', $vecUTimu)
                                ->with('postrojba')
                                ->orderBy('prezime')
                                ->get()
                                ->mapWithKeys(fn ($v) => [
                                    $v->id => $v->prezime . ' ' . $v->ime . ' • ' . ($v->postrojba?->naziv ?? '?')
                                ])
                                ->toArray();
                        })
                        ->searchable()
                        ->required(),
                    
                    Select::make('uloga')
                        ->label('Uloga u timu')
                        ->options([
                            'vatrogasac' => '👤 Vatrogasac',
                            'zapovjednik' => '👑 Zapovjednik',
                        ])
                        ->default('vatrogasac')
                        ->required(),
                    
                    Textarea::make('napomena')
                        ->label('Napomena (opcionalno)')
                        ->rows(2),
                ])
                ->action(function (array $data) {
                    TimClanstvo::create([
                        'tim_id' => $this->record->id,
                        'vatrogasac_id' => $data['vatrogasac_id'],
                        'uloga' => $data['uloga'],
                        'usao_u' => now(),
                        'napomena' => $data['napomena'] ?? null,
                    ]);

                    $vatrogasac = Vatrogasac::find($data['vatrogasac_id']);

                    Notification::make()
                        ->title('Vatrogasac dodan')
                        ->body($vatrogasac->puno_ime . ' je dodan u tim ' . $this->record->naziv)
                        ->success()
                        ->send();
                }),

            // ===== PROMIJENI STATUS =====
            ActionGroup::make([
                Action::make('statusPolazak')
                    ->label('🚒 Polazak')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(fn () => $this->promijeniStatus('polazak', 'Tim krenuo na intervenciju')),

                Action::make('statusNaMjestu')
                    ->label('📍 Na mjestu')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn () => $this->promijeniStatus('na_mjestu', 'Tim stigao na mjesto')),

                Action::make('statusZavrsili')
                    ->label('✅ Završili intervenciju')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn () => $this->promijeniStatus('intervencija_zavrsena', 'Tim završio intervenciju')),

                Action::make('statusPovratak')
                    ->label('↩️ Povratak')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(fn () => $this->promijeniStatus('povratak', 'Tim se vraća')),

                Action::make('statusOdmor')
                    ->label('😴 Odmor')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(fn () => $this->promijeniStatus('odmor', 'Tim na odmoru')),

                Action::make('statusUBazi')
                    ->label('🏠 U bazi')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(fn () => $this->promijeniStatus('cekanje_u_bazi', 'Tim u bazi, čeka')),

                Action::make('statusRaspusti')
                    ->label('🚪 Raspusti tim')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('Tim će biti raspušten. Svi članovi će automatski izaći iz tima.')
                    ->action(function () {
                        $this->promijeniStatus('raspusten', 'Tim raspušten');
                        
                        // Sve aktivne članove iznesi iz tima
                        TimClanstvo::where('tim_id', $this->record->id)
                            ->whereNull('izasao_u')
                            ->update(['izasao_u' => now()]);
                        
                        // Sva aktivna vozila iznesi iz tima
                        \App\Models\TimVozilo::where('tim_id', $this->record->id)
                            ->whereNull('skinuto_u')
                            ->update(['skinuto_u' => now()]);
                        
                        // Postavi vrijeme raspuštanja
                        $this->record->update([
                            'vrijeme_raspustanja' => now(),
                        ]);
                    }),
            ])
                ->label('🔄 Promijeni status')
                ->button()
                ->color('primary'),
            
            DeleteAction::make(),
        ];
    }

    /**
     * Pomoćna funkcija — promjena statusa tima + log.
     */
    protected function promijeniStatus(string $noviStatus, string $opis): void
    {
        $stariStatus = $this->record->trenutni_status;

        $this->record->update(['trenutni_status' => $noviStatus]);

        TimStatusLog::create([
            'tim_id' => $this->record->id,
            'status' => $noviStatus,
            'vrijeme' => now(),
            'autor_id' => auth()->id(),
            'intervencija_id' => $this->record->intervencija_id,
            'napomena' => $opis,
        ]);

        Notification::make()
            ->title('Status promijenjen')
            ->body($this->record->naziv . ': ' . $stariStatus . ' → ' . $noviStatus)
            ->success()
            ->send();
    }

    /**
     * Akcija — Skini vatrogasca iz tima.
     */
    public function skiniClana(int $clanstvoId): void
    {
        $clanstvo = TimClanstvo::find($clanstvoId);
        if (!$clanstvo || $clanstvo->izasao_u) {
            return;
        }

        $clanstvo->update(['izasao_u' => now()]);

        Notification::make()
            ->title('Vatrogasac uklonjen')
            ->body($clanstvo->vatrogasac->puno_ime . ' više nije u timu (povijest sačuvana)')
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $tim = $this->record;

        $trenutniClanovi = TimClanstvo::where('tim_id', $tim->id)
            ->whereNull('izasao_u')
            ->with(['vatrogasac.postrojba'])
            ->orderBy('uloga', 'desc') // zapovjednik prvi
            ->get();

        $povijestClanstva = TimClanstvo::where('tim_id', $tim->id)
            ->whereNotNull('izasao_u')
            ->with(['vatrogasac.postrojba'])
            ->orderBy('izasao_u', 'desc')
            ->limit(20)
            ->get();

        $statusLog = TimStatusLog::where('tim_id', $tim->id)
            ->with(['autor'])
            ->orderBy('vrijeme', 'desc')
            ->get();

        return [
            'tim' => $tim,
            'trenutniClanovi' => $trenutniClanovi,
            'povijestClanstva' => $povijestClanstva,
            'statusLog' => $statusLog,
        ];
    }
}