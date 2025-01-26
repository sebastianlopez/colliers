<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'     => 'Administrador',
                'email'    => 'admin@rhiss.net',
                'password' => bcrypt('admin12345'),
            ],
            [
                'name'     => 'Soporte',
                'email'    => 'soporte@rhiss.net',
                'password' => bcrypt('soporte12345'),
            ]
        ]);
    }
}
