<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FilmActorSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();


        $filmIds = range(1, 10);
        $actorIds = range(1, 10);

        foreach ($filmIds as $filmId) {
            $actorsCount = $faker->numberBetween(1, 3);
            $selectedActorIds = $faker->randomElements($actorIds, $actorsCount);

            foreach ($selectedActorIds as $actorId) {
                DB::table('films_actors')->insert([
                    'film_id' => $filmId,
                    'actor_id' => $actorId,
                    "created_at" => now()->setTimezone("Europe/Madrid"),
                ]);
            }
        }
    }
}
