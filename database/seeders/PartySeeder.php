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
                'name' => "Deportes",
                'rules' => "No insultar"
            ],
            [
                'name' => "Shooters",
                'rules' => "Prohivido el fuego amigo"
            ],
            [
                'name' => "Accion",
                'rules' => "No spoilear historias"
            ],
            [
                'name' => "SoulsLike",
                'rules' => "Maximo 10 trys por Boss"
            ],
            ]);
    }
}
