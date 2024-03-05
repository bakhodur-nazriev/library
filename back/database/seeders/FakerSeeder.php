<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class FakerSeeder extends Seeder
{

    public function run(): void
    {

        $faker = Factory::create();

        for ($i = 0; $i < 100; $i++)
        {
            //
             User::query()
                ->create([
                'name' => $faker->userName,
                'email' => $faker->email,
                 'password' => Hash::make('12345678'),
            ]);

             //
            Book::query()
                ->create([
                    'title' => $faker->sentence,
                    'description' => $faker->paragraph,
                    'ISBN' => $faker->isbn13,
                    'published_at' => $faker->dateTimeBetween('-20 years', 'now'),
                    'genre' => $faker->randomElement(['Fiction', 'Non-Fiction', 'Sci-Fi', 'Fantasy']),
                    'language' => $faker->languageCode,
                    'pages' => $faker->numberBetween(50, 1000),
                    'publisher' => $faker->company,
                ]);

            //
            Author::query()
                ->create([
                    'initials' => $faker->firstName,
                    'nationality' => $faker->country,
                    'biography' => $faker->text,
                    'date_of_birth' => $faker->date,
                ]);

        }

        //
        $randomAuthors = Author::query()
            ->inRandomOrder()
            ->limit(50)
            ->get();
        //
        $randomBooks = Book::query()
            ->inRandomOrder()
            ->limit(50)
            ->get();
        //
        $randomAuthors->each(function ($author) use ($randomBooks) {
            $book = $randomBooks->random();
            $book->authors()->attach($author);
        });
    }
}
