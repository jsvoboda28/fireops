<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Resetiraj cache za uloge i dozvole
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Pomoćne liste permissions
        $sveDojava = [
            'ViewAny:Dojava', 'View:Dojava', 'Create:Dojava', 'Update:Dojava',
            'Delete:Dojava', 'DeleteAny:Dojava', 'Restore:Dojava', 'RestoreAny:Dojava',
            'ForceDelete:Dojava', 'ForceDeleteAny:Dojava',
        ];

        $sveDogadjaj = [
            'ViewAny:OperativniDogadjaj', 'View:OperativniDogadjaj',
            'Create:OperativniDogadjaj', 'Update:OperativniDogadjaj',
            'Delete:OperativniDogadjaj', 'DeleteAny:OperativniDogadjaj',
        ];

        $svePostrojba = [
            'ViewAny:Postrojba', 'View:Postrojba',
            'Create:Postrojba', 'Update:Postrojba',
            'Delete:Postrojba', 'DeleteAny:Postrojba',
        ];

        $sveVatrogasac = [
            'ViewAny:Vatrogasac', 'View:Vatrogasac',
            'Create:Vatrogasac', 'Update:Vatrogasac',
            'Delete:Vatrogasac', 'DeleteAny:Vatrogasac',
        ];

        $sveVozilo = [
            'ViewAny:Vozilo', 'View:Vozilo',
            'Create:Vozilo', 'Update:Vozilo',
            'Delete:Vozilo', 'DeleteAny:Vozilo',
        ];

        $samoPregled = function(string $resource): array {
            return ["ViewAny:{$resource}", "View:{$resource}"];
        };

        // ========================================
        // 1. ŽUPANIJSKI ZAPOVJEDNIK — VZ PSŽ
        // ========================================
        $zupZapovjednik = Role::firstOrCreate(['name' => 'zupanijski_zapovjednik', 'guard_name' => 'web']);
        $zupZapovjednik->syncPermissions(array_merge(
            $sveDojava,
            $sveDogadjaj,
            $samoPregled('Postrojba'),
            $samoPregled('Vatrogasac'),
            $samoPregled('Vozilo'),
            $samoPregled('Jls'),
        ));

        // ========================================
        // 2. ŽUPANIJSKI DISPEČER — Centar 112 ili ŽVOC
        // ========================================
        $zupDispecer = Role::firstOrCreate(['name' => 'zupanijski_dispecer', 'guard_name' => 'web']);
        $zupDispecer->syncPermissions(array_merge(
            $sveDojava,
            [
                'ViewAny:OperativniDogadjaj', 'View:OperativniDogadjaj',
                'Create:OperativniDogadjaj', 'Update:OperativniDogadjaj',
            ],
            $samoPregled('Postrojba'),
            $samoPregled('Vatrogasac'),
            $samoPregled('Vozilo'),
        ));

        // ========================================
        // 3. PODRUČNI ZAPOVJEDNIK — VZP (Pakrac-Lipik, Požeština)
        // ========================================
        $podZapovjednik = Role::firstOrCreate(['name' => 'podrucni_zapovjednik', 'guard_name' => 'web']);
        $podZapovjednik->syncPermissions(array_merge(
            $sveDojava,
            $sveDogadjaj,
            $samoPregled('Postrojba'),
            $samoPregled('Vatrogasac'),
            $samoPregled('Vozilo'),
        ));

        // ========================================
        // 4. OPĆINSKI ZAPOVJEDNIK — VZG (jedna JLS)
        // ========================================
        $opcZapovjednik = Role::firstOrCreate(['name' => 'opcinski_zapovjednik', 'guard_name' => 'web']);
        $opcZapovjednik->syncPermissions(array_merge(
            $samoPregled('Dojava'),
            ['Update:Dojava'],
            $samoPregled('OperativniDogadjaj'),
            ['Update:OperativniDogadjaj'],
            $samoPregled('Postrojba'),
            $samoPregled('Vatrogasac'),
            $samoPregled('Vozilo'),
        ));

        // ========================================
        // 5. DISPEČER (područni)
        // ========================================
        $dispecer = Role::firstOrCreate(['name' => 'dispecer', 'guard_name' => 'web']);
        $dispecer->syncPermissions(array_merge(
            $sveDojava,
            [
                'ViewAny:OperativniDogadjaj', 'View:OperativniDogadjaj',
                'Update:OperativniDogadjaj',
            ],
            $samoPregled('Postrojba'),
            $samoPregled('Vatrogasac'),
            $samoPregled('Vozilo'),
        ));

        // ========================================
        // 6. OPERATER 112 — samo unos dojava
        // ========================================
        $operater = Role::firstOrCreate(['name' => 'operater_112', 'guard_name' => 'web']);
        $operater->syncPermissions([
            'ViewAny:Dojava',
            'View:Dojava',
            'Create:Dojava',
            'Update:Dojava',
        ]);

        // ========================================
        // 7. VODITELJ DOGAĐAJA (staro ime — zadržano za kompatibilnost, isto kao podrucni_zapovjednik)
        // ========================================
        $voditelj = Role::firstOrCreate(['name' => 'voditelj_dogadjaja', 'guard_name' => 'web']);
        $voditelj->syncPermissions(array_merge(
            $sveDojava,
            $sveDogadjaj,
            $samoPregled('Postrojba'),
            $samoPregled('Vatrogasac'),
            $samoPregled('Vozilo'),
        ));

        $this->command->info('Uloge uspješno kreirane:');
        $this->command->info('  1. zupanijski_zapovjednik (VZ PSŽ)');
        $this->command->info('  2. zupanijski_dispecer (Centar 112)');
        $this->command->info('  3. podrucni_zapovjednik (VZP)');
        $this->command->info('  4. opcinski_zapovjednik (VZG / Općina)');
        $this->command->info('  5. dispecer');
        $this->command->info('  6. operater_112');
        $this->command->info('  7. voditelj_dogadjaja (legacy)');
    }
}