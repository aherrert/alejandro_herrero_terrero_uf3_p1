<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FilmFakerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('films')->insert([
                'name' => $faker->name,
                'year' => $faker->year,
                'genre' => $faker->randomElement(["Action", "Drama", "Comedy", "Adventure", "Horror", "Science Fiction", "Fantasy", "Romance", "Mystery", "Thriller"]),
                'country' => $faker->country,
                'duration' => $faker->numberBetween(60, 240),
                'img_url' => $faker->imageUrl(),
                "created_at" => now()->setTimezone("Europe/Madrid"),
            ]);
        }
    }
}
