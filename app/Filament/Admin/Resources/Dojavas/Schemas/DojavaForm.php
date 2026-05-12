<?php

namespace App\Filament\Admin\Resources\Dojavas\Schemas;

use App\Models\KucniBroj;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;

class DojavaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija')
                    ->columns(2)
                    ->schema([
                        TextInput::make('broj_dojave')
                            ->label('Broj dojave')
                            ->maxLength(20)
                            ->placeholder('Auto-generira se ako je prazno')
                            ->helperText('Ostavi prazno za automatsko generiranje (npr. 2026-001234)')
                            ->unique(ignoreRecord: true),

                        Select::make('kanal_dojave')
                            ->label('Kanal dojave')
                            ->required()
                            ->options([
                                '112' => '112',
                                'telefon' => 'Telefon',
                                'osobno' => 'Osobno',
                                'druga_sluzba' => 'Druga služba',
                                'sms_web' => 'SMS / Web',
                            ])
                            ->default('112'),

                        Select::make('dogadjaj_id')
                            ->label('Operativni događaj')
                            ->relationship(
                                'dogadjaj',
                                'naziv',
                                fn ($query) => $query->whereIn('status', ['aktivan', 'pracenje'])
                            )
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                            ->placeholder('Bez događaja (samostalna dojava)')
                            ->helperText('Ako je dojava povezana s nekim aktivnim događajem, odaberi ga ovdje'),
                    ]),

                Section::make('📍 Lokacija dojave')
                    ->description('Glavni način: pretraga RPJ adresa. Alternativa: klik na kartu za parcele, šume, raskršća.')
                    ->columns(2)
                    ->schema([

                        Select::make('rpj_search')
                            ->label('🔍 Pretraga adrese (RPJ — 34 000+ adresa)')
                            ->placeholder('Tipkaj ulicu, broj ili naselje...')
                            ->searchable()
                            ->live()
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->helperText('Najbrži način — odaberi adresu, sve se popuni automatski.')
                            ->getOptionLabelUsing(function ($value): ?string {
                                if (!$value) return null;
                                $kb = KucniBroj::with(['ulica', 'naselje'])->find($value);
                                if (!$kb) return null;
                                return ($kb->ulica?->naziv ?? '?')
                                    . ' ' . $kb->broj
                                    . ', ' . ($kb->naselje?->naziv ?? '?');
                            })
                            ->getSearchResultsUsing(function (string $search): array {
                                if (strlen($search) < 2) return [];

                                $query = KucniBroj::query()
                                    ->select('kucni_brojevi.*')
                                    ->join('ulice', 'ulice.id', '=', 'kucni_brojevi.ulica_id')
                                    ->join('naselja', 'naselja.id', '=', 'kucni_brojevi.naselje_id')
                                    ->with(['ulica', 'naselje']);

                                $parts = preg_split('/\s+/', trim($search));

                                if (count($parts) >= 2 && is_numeric(preg_replace('/[^\d]/', '', end($parts)))) {
                                    $broj = array_pop($parts);
                                    $ulica = implode(' ', $parts);
                                    $query->where('ulice.naziv', 'ILIKE', "%{$ulica}%")
                                          ->where('kucni_brojevi.broj', 'ILIKE', "{$broj}%");
                                } else {
                                    $query->where(function ($q) use ($search) {
                                        $q->where('ulice.naziv', 'ILIKE', "%{$search}%")
                                          ->orWhere('naselja.naziv', 'ILIKE', "%{$search}%");
                                    });
                                }

                                return $query
                                    ->orderBy('ulice.naziv')
                                    ->orderByRaw('LENGTH(kucni_brojevi.broj)')
                                    ->orderBy('kucni_brojevi.broj')
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(function ($kb) {
                                        $label = ($kb->ulica?->naziv ?? '?') . ' ' . $kb->broj . ', ' . ($kb->naselje?->naziv ?? '?');
                                        return [$kb->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (!$state) return;

                                $kb = KucniBroj::with(['ulica', 'naselje.jls'])->find($state);
                                if (!$kb) return;

                                $set('adresa', trim(($kb->ulica?->naziv ?? '') . ' ' . $kb->broj, ' '));
                                $set('opcina', $kb->naselje?->naziv);
                                $set('latitude', (float) $kb->latitude);
                                $set('longitude', (float) $kb->longitude);
                                $set('location', ['lat' => (float) $kb->latitude, 'lng' => (float) $kb->longitude]);

                                if ($kb->naselje?->jls_id) {
                                    $set('jls_id', $kb->naselje->jls_id);
                                }
                            }),

                        Map::make('location')
                            ->label('🗺 Ili odaberi na karti (parcele, šume, izvanlinijska mjesta)')
                            ->columnSpanFull()
                            ->defaultLocation(45.35, 17.65)
                            ->zoom(10)
                            ->draggable(true)
                            ->clickable(true)
                            ->geoMan(false)
                            ->tilesUrl('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
                            ->extraStyles(['min-height: 400px', 'border-radius: 8px'])
                            ->helperText('Klikni bilo gdje na karti — GPS i adresa će se popuniti automatski (reverse geocoding kroz OpenStreetMap).')
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (!$state || !isset($state['lat']) || !isset($state['lng'])) return;

                                $lat = (float) $state['lat'];
                                $lng = (float) $state['lng'];

                                $set('latitude', $lat);
                                $set('longitude', $lng);

                                try {
                                    $response = Http::withHeaders([
                                        'User-Agent' => 'FireOps PSŽ Dispatch System',
                                    ])->timeout(5)->get('https://nominatim.openstreetmap.org/reverse', [
                                        'lat' => $lat,
                                        'lon' => $lng,
                                        'format' => 'json',
                                        'addressdetails' => 1,
                                        'accept-language' => 'hr',
                                    ]);

                                    if ($response->successful()) {
                                        $data = $response->json();
                                        $address = $data['address'] ?? [];

                                        $street = $address['road'] ?? $address['pedestrian'] ?? '';
                                        $houseNumber = $address['house_number'] ?? '';
                                        $adresa = trim($street . ' ' . $houseNumber);
                                        
                                        if (empty($adresa)) {
                                            $adresa = $address['hamlet'] 
                                                ?? $address['suburb'] 
                                                ?? $address['neighbourhood'] 
                                                ?? 'GPS: ' . round($lat, 5) . ', ' . round($lng, 5);
                                        }

                                        $naselje = $address['village'] 
                                            ?? $address['town'] 
                                            ?? $address['city'] 
                                            ?? $address['hamlet'] 
                                            ?? '';

                                        $set('adresa', $adresa);
                                        if ($naselje) {
                                            $set('opcina', $naselje);
                                        }

                                        if ($naselje) {
                                            $jls = \App\Models\Jls::where('naziv', 'ILIKE', "%{$naselje}%")->first();
                                            if ($jls) {
                                                $set('jls_id', $jls->id);
                                            }
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // Tiha greška
                                }
                            })
                            ->afterStateHydrated(function ($state, $record, Set $set) {
                                if ($record && $record->latitude && $record->longitude) {
                                    $set('location', [
                                        'lat' => (float) $record->latitude,
                                        'lng' => (float) $record->longitude,
                                    ]);
                                }
                            })
                            ->dehydrated(false),

                        TextInput::make('adresa')
                            ->label('Adresa')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->placeholder('npr. Industrijska 12'),

                        Select::make('jls_id')
                            ->label('JLS')
                            ->relationship('jls', 'naziv', fn ($query) => $query->where('aktivan', true))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->placeholder('Odaberi JLS...'),

                        TextInput::make('opcina')
                            ->label('Naselje')
                            ->maxLength(100)
                            ->placeholder('npr. Pakrac, Antunovac'),

                        TextInput::make('latitude')
                            ->label('Latitude (GPS)')
                            ->numeric()
                            ->step('any')
                            ->readOnly()
                            ->placeholder('Auto'),

                        TextInput::make('longitude')
                            ->label('Longitude (GPS)')
                            ->numeric()
                            ->step('any')
                            ->readOnly()
                            ->placeholder('Auto'),
                    ]),

                Section::make('Klasifikacija')
                    ->columns(3)
                    ->schema([
                        Select::make('tip_nepogode')
                            ->label('Tip nepogode')
                            ->required()
                            ->options([
                                'olujno_nevrijeme' => 'Olujno nevrijeme',
                                'poplava' => 'Poplava',
                                'pozar' => 'Požar',
                                'snijeg_led' => 'Snijeg / led',
                                'klizište' => 'Klizište',
                                'tuca' => 'Tuča',
                                'potres' => 'Potres',
                                'ostalo' => 'Ostalo',
                            ]),

                        Select::make('ugrozenost_ljudi')
                            ->label('Ugroženost ljudi')
                            ->required()
                            ->options([
                                'da' => 'DA — ima ugroženih',
                                'ne' => 'NE — nema ugroženih',
                                'ne_znam' => 'Ne znam',
                            ])
                            ->default('ne_znam'),

                        Select::make('prioritet')
                            ->label('Prioritet')
                            ->required()
                            ->options([
                                'kriticna' => '🔴 Kritična',
                                'visoka' => '🟡 Visoka',
                                'standardna' => '🟢 Standardna',
                            ])
                            ->default('standardna'),
                    ]),

                Section::make('Opis')
                    ->schema([
                        Textarea::make('opis')
                            ->label('Opis situacije')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Što se točno dogodilo, što je prijavitelj rekao...'),
                    ]),

                Section::make('Prijavitelj')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('prijavitelj_ime')
                            ->label('Ime i prezime')
                            ->maxLength(200),

                        TextInput::make('prijavitelj_telefon')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(50),

                        Toggle::make('prijavitelj_anoniman')
                            ->label('Anoniman prijavitelj')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status i vremena')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'zaprimljena' => 'Zaprimljena',
                                'dodijeljena' => 'Dodijeljena timu',
                                'u_tijeku' => 'U tijeku',
                                'zavrsena' => 'Završena',
                            ])
                            ->default('zaprimljena'),

                        DateTimePicker::make('vrijeme_zaprimanja')
                            ->label('Vrijeme zaprimanja')
                            ->default(now())
                            ->helperText('Auto-popunjava se na trenutno vrijeme'),

                        DateTimePicker::make('vrijeme_zatvaranja')
                            ->label('Vrijeme zatvaranja'),
                    ]),
            ]);
    }
}