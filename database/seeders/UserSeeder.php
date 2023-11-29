<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id'=> 1,
                'name' => "David",
                'surname' => "Soto",
                'nickname' => "s0usa",
                'email' => "david@david.com",
                'password' => encrypt("123456"),
                'age' => "37",
                'role_id' => 1
            ],
            [
                'id' => 2,
                'name' => "Sergio",
                'surname' => "Soto",
                'nickname' => "Sergii",
                'email' => "sergio@sergio.com",
                'password' => encrypt("123456"),
                'age' => "35",
                'role_id' => 2
            ],
            [
                'id' => 3,
                'name' => "David",
                'surname' => "Blanco",
                'nickname' => "Dablaan",
                'email' => "blanco@blanco.com",
                'password' => encrypt("123456"),
                'age' => "40",
                'role_id' => 2
            ],
            [
                'id' => 4,
                'name' => "Alejandro",
                'surname' => "Laguia",
                'nickname' => "Dreck",
                'email' => "alejandro@alejandro.com",
                'password' => encrypt("123456"),
                'age' => "28",
                'role_id' => 2
            ],
            ]);
    }
}
