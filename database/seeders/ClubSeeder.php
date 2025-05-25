<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\Group;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar el primer grupo del sistema (ej: Vegas del Genil)
        $group = Group::first();

        if (!$group) {
            $this->command->error('No hay grupos en la base de datos.');
            return;
        }

        $nombres = [
            'CD Bola de Partido La Zubia',
            'CD Huétor Vega Tenis de Mesa',
            'Club Tenis de Mesa Alfacar',
            'CTM Ciudad de Granada 2012',
            'ADA Guadix Tenis de Mesa'
        ];

        foreach ($nombres as $nombre) {
            Club::create([
                'group_id' => $group->id,
                'name' => $nombre
            ]);
        }

        $this->command->info('Clubes creados correctamente para el grupo: ' . $group->name);
    }
}

