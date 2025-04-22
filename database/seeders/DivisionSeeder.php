<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $divisions = [
            '🥇 División de Honor',
            '🥈 Primera División Nacional',
            '🥉 Segunda División Nacional',
            '🏅 Primera División Autonómica (o Regional)',
            '🏅 Segunda División Autonómica (o Regional)',
            '🏓 Liga Local / Torneo Local',
        ];

        foreach ($divisions as $name) {
            Division::create(['name' => $name]);
        }
    }
}
