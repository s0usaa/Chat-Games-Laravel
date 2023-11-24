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
                'title' => "CS:GO 2",
                'genre' => "Shooter",
                'platform' => "Steam"
            ],
            [
                'title' => "Hollow Knight",
                'genre' => "Metroidvania",
                'platform' => "Nitendo Switch"
            ],
            [
                'title' => "Ghost of Tsushima",
                'genre' => "Action/Adventure",
                'platform' => "PS5"
            ],
            [
                'title' => "Sea of Stars",
                'genre' => "RPG",
                'platform' => "Steam"
            ],
            [
                'title' => "The last of us",
                'genre' => "Action/Adventure",
                'platform' => "PS5"
            ],
            [
                'title' => "FC 24",
                'genre' => "Sport",
                'platform' => "Xbox Series X"
            ],
        ]);
    }
}
