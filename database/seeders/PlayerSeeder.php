<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use Faker\Factory as Faker;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $rubbers = ['Tenergy 05', 'Dignics 64', 'Hurricane 3', 'Yasaka Rakza', 'Donic Bluefire'];
        $paddles = ['OFF', 'ALL+', 'DEF', 'OFF-', 'ALL'];
        $divisions = ['Primera', 'Segunda', 'Tercera', 'Promesas'];

        for ($i = 0; $i < 50; $i++) {
            $played = $faker->numberBetween(5, 30);
            $won = $faker->numberBetween(0, $played);
            $lost = $played - $won;

            Player::create([
                'first_name'     => $faker->firstName,
                'last_name'      => $faker->lastName,
                'photo'          => 'https://i.pravatar.cc/150?img=' . $faker->numberBetween(1, 70),
                'about'          => $faker->sentence,
                'rubber_style'   => $faker->randomElement($rubbers),
                'paddle_type'    => $faker->randomElement($paddles),
                'matches_played' => $played,
                'matches_won'    => $won,
                'matches_lost'   => $lost,
                'division_id'       => $faker->numberBetween(1, 6),
            ]);
        }
    }
}
