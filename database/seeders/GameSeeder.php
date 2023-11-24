<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('games')->insert([
            [
                'id' => 1,
                'title' => "CS:GO 2",
                'genre' => "Shooter",
                'platform' => "Steam"
            ],
            [
                'id' => 2,
                'title' => "Hollow Knight",
                'genre' => "Metroidvania",
                'platform' => "Nitendo Switch"
            ],
            [
                'id' => 3,
                'title' => "Ghost of Tsushima",
                'genre' => "Action/Adventure",
                'platform' => "PS5"
            ],
            [
                'id' => 4,
                'title' => "Sea of Stars",
                'genre' => "RPG",
                'platform' => "Steam"
            ],
            [
                'id' => 5,
                'title' => "The last of us",
                'genre' => "Action/Adventure",
                'platform' => "PS5"
            ],
            [
                'id' => 6,
                'title' => "FC 24",
                'genre' => "Sport",
                'platform' => "Xbox Series X"
            ],
        ]);
    }
}
