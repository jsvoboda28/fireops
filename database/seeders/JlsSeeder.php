<?php

namespace Database\Seeders;

use App\Models\Jls;
use Illuminate\Database\Seeder;

class JlsSeeder extends Seeder
{
    public function run(): void
    {
        $jls = [
            // Gradovi
            ['naziv' => 'Požega', 'tip' => 'grad'],
            ['naziv' => 'Pakrac', 'tip' => 'grad'],
            ['naziv' => 'Lipik', 'tip' => 'grad'],
            ['naziv' => 'Pleternica', 'tip' => 'grad'],
            ['naziv' => 'Kutjevo', 'tip' => 'grad'],
            
            // Općine
            ['naziv' => 'Brestovac', 'tip' => 'opcina'],
            ['naziv' => 'Čaglin', 'tip' => 'opcina'],
            ['naziv' => 'Jakšić', 'tip' => 'opcina'],
            ['naziv' => 'Kaptol', 'tip' => 'opcina'],
            ['naziv' => 'Velika', 'tip' => 'opcina'],
        ];

        foreach ($jls as $item) {
            Jls::firstOrCreate(
                ['naziv' => $item['naziv']],
                $item
            );
        }

        $this->command->info('Kreirano ' . count($jls) . ' JLS-ova za Požeško-slavonsku županiju.');
    }
}