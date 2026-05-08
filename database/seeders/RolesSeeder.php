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

        // ========================================
        // OPERATER 112
        // ========================================
        $operater = Role::firstOrCreate(['name' => 'operater_112', 'guard_name' => 'web']);
        $operater->syncPermissions([
            'ViewAny:Dojava',
            'View:Dojava',
            'Create:Dojava',
            'Update:Dojava',
        ]);

        // ========================================
        // DISPEČER
        // ========================================
        $dispecer = Role::firstOrCreate(['name' => 'dispecer', 'guard_name' => 'web']);
        $dispecer->syncPermissions([
            'ViewAny:Dojava',
            'View:Dojava',
            'Create:Dojava',
            'Update:Dojava',
            'Delete:Dojava',
            'DeleteAny:Dojava',
            'Restore:Dojava',
            'RestoreAny:Dojava',
        ]);

        // ========================================
        // VODITELJ DOGAĐAJA
        // ========================================
        $voditelj = Role::firstOrCreate(['name' => 'voditelj_dogadjaja', 'guard_name' => 'web']);
        $voditelj->syncPermissions([
            'ViewAny:Dojava',
            'View:Dojava',
            'Create:Dojava',
            'Update:Dojava',
            'Delete:Dojava',
            'DeleteAny:Dojava',
            'Restore:Dojava',
            'RestoreAny:Dojava',
            'ForceDelete:Dojava',
            'ForceDeleteAny:Dojava',
            'ViewAny:Role',
            'View:Role',
        ]);

        $this->command->info('Uloge uspješno kreirane: operater_112, dispecer, voditelj_dogadjaja');
    }
}