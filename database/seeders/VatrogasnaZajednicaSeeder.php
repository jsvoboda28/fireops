<?php

namespace Database\Seeders;

use App\Models\Jls;
use App\Models\VatrogasnaZajednica;
use Illuminate\Database\Seeder;

class VatrogasnaZajednicaSeeder extends Seeder
{
    public function run(): void
    {
        // Mapiraj JLS-ove po nazivu
        $jlsMap = Jls::pluck('id', 'naziv')->toArray();

        // 1. ŽUPANIJSKA RAZINA — VZ PSŽ
        $vzPsz = VatrogasnaZajednica::firstOrCreate(
            ['naziv' => 'Vatrogasna zajednica Požeško-slavonske županije'],
            [
                'skraceni_naziv' => 'VZ PSŽ',
                'tip' => 'zupanijska',
                'roditelj_id' => null,
                'oib' => '02892060671',
                'adresa' => 'Republike Hrvatske 1b, Požega',
                'aktivna' => true,
            ]
        );

        // 2. PODRUČNA — VZG Požega (pokriva samo Požegu)
        $vzgPozega = VatrogasnaZajednica::firstOrCreate(
            ['naziv' => 'Vatrogasna zajednica Grada Požege'],
            [
                'skraceni_naziv' => 'VZG Požega',
                'tip' => 'podrucna',
                'roditelj_id' => $vzPsz->id,
                'oib' => '90566798892',
                'adresa' => 'Industrijska ulica 44, Požega',
                'aktivna' => true,
            ]
        );

        // 3. PODRUČNA — VZP Požeština (Brestovac, Čaglin, Jakšić, Kaptol, Velika, Pleternica, Kutjevo)
        $vzpPozestina = VatrogasnaZajednica::firstOrCreate(
            ['naziv' => 'Vatrogasna zajednica područja Požeštine'],
            [
                'skraceni_naziv' => 'VZP Požeština',
                'tip' => 'podrucna',
                'roditelj_id' => $vzPsz->id,
                'oib' => '01798032659',
                'adresa' => 'Republike Hrvatske 1b, Požega',
                'aktivna' => true,
            ]
        );

        // 4. PODRUČNA — VZP Pakrac-Lipik (Pakrac, Lipik)
        $vzpPakracLipik = VatrogasnaZajednica::firstOrCreate(
            ['naziv' => 'Vatrogasna zajednica područja Pakrac-Lipik'],
            [
                'skraceni_naziv' => 'VZP Pakrac-Lipik',
                'tip' => 'podrucna',
                'roditelj_id' => $vzPsz->id,
                'oib' => '99234890578',
                'adresa' => 'Trg bana J. Jelačića 18, Pakrac',
                'aktivna' => true,
            ]
        );

        // ====================================================================
        // POVEZIVANJE VZ-ova S JLS-ovima
        // ====================================================================

        // VZG Požega → samo Požega
        if (isset($jlsMap['Požega'])) {
            $vzgPozega->jlsovi()->syncWithoutDetaching([$jlsMap['Požega']]);
        }

        // VZP Požeština → 7 JLS-ova
        $pozestinaJls = ['Brestovac', 'Čaglin', 'Jakšić', 'Kaptol', 'Velika', 'Pleternica', 'Kutjevo'];
        $pozestinaJlsIdovi = [];
        foreach ($pozestinaJls as $naziv) {
            if (isset($jlsMap[$naziv])) {
                $pozestinaJlsIdovi[] = $jlsMap[$naziv];
            }
        }
        $vzpPozestina->jlsovi()->syncWithoutDetaching($pozestinaJlsIdovi);

        // VZP Pakrac-Lipik → 2 JLS-a
        $pakracLipikJls = ['Pakrac', 'Lipik'];
        $pakracLipikJlsIdovi = [];
        foreach ($pakracLipikJls as $naziv) {
            if (isset($jlsMap[$naziv])) {
                $pakracLipikJlsIdovi[] = $jlsMap[$naziv];
            }
        }
        $vzpPakracLipik->jlsovi()->syncWithoutDetaching($pakracLipikJlsIdovi);

        $this->command->info('Kreirano 4 vatrogasne zajednice:');
        $this->command->info('  - VZ PSŽ (županijska)');
        $this->command->info('  - VZG Požega (područna, 1 JLS)');
        $this->command->info('  - VZP Požeština (područna, 7 JLS)');
        $this->command->info('  - VZP Pakrac-Lipik (područna, 2 JLS)');
    }
}