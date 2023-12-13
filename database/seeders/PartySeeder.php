<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('parties')->insert([
            [
                'id' => 1,
                'name' => "Deportes",
                'rules' => "No insultar",
                'game_id' => 6,
            ],
            [
                'id' => 2,
                'name' => "Shooters",
                'rules' => "Prohivido el fuego amigo",
                'game_id' => 1,
            ],
            [
                'id' => 3,
                'name' => "Accion",
                'rules' => "No spoilear historias",
                'game_id' => 3,
            ],
            [
                'id' => 4,
                'name' => "SoulsLike",
                'rules' => "Maximo 10 trys por Boss",
                'game_id' => 2,
            ],
            ]);
    }
}
