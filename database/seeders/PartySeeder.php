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
                'rules' => "No insultar"
            ],
            [
                'id' => 2,
                'name' => "Shooters",
                'rules' => "Prohivido el fuego amigo"
            ],
            [
                'id' => 3,
                'name' => "Accion",
                'rules' => "No spoilear historias"
            ],
            [
                'id' => 4,
                'name' => "SoulsLike",
                'rules' => "Maximo 10 trys por Boss"
            ],
            ]);
    }
}
