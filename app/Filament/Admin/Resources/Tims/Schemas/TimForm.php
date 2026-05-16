<?php

namespace App\Filament\Admin\Resources\Tims\Schemas;

use App\Models\Intervencija;
use App\Models\Postrojba;
use App\Models\Vatrogasac;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TimForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Section::make('Osnovni podaci')
                    ->columns(2)
                    ->schema([
                        TextInput::make('naziv')
                            ->label('Naziv tima')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('npr. Tim Kaptol-1, Tim Sjever'),
                        
                        Select::make('trenutni_status')
                            ->label('Trenutni status')
                            ->required()
                            ->options([
                                'formiran' => '🆕 Formiran',
                                'polazak' => '🚒 Polazak',
                                'na_mjestu' => '📍 Na mjestu',
                                'intervencija_zavrsena' => '✅ Završili intervenciju',
                                'povratak' => '↩️ Povratak',
                                'odmor' => '😴 Odmor',
                                'cekanje_u_bazi' => '🏠 Čekanje u bazi',
                                'raspusten' => '🚪 Raspušten',
                            ])
                            ->default('formiran'),

                        Select::make('intervencija_id')
                            ->label('Intervencija (prazno = u bazi)')
                            ->options(Intervencija::where('status', 'aktivna')->pluck('naziv', 'id'))
                            ->searchable()
                            ->placeholder('Tim nije na intervenciji'),

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
                            ->columnSpanFull(),

                        Textarea::make('zadatak')
                            ->label('Zadatak tima')
                            ->rows(2)
                            ->columnSpanFull()
                            ->placeholder('npr. Gašenje glavnog požara'),
                    ]),

                Section::make('Vremena')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('vrijeme_formiranja')
                            ->label('Vrijeme formiranja')
                            ->default(now()),

                        DateTimePicker::make('vrijeme_raspustanja')
                            ->label('Vrijeme raspuštanja'),

                        Textarea::make('napomena')
                            ->label('Napomena')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}