<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 1,
            'nama' => 'admin'
        ]);

        Role::create([
            'id' => 2,
            'nama' => 'user'
        ]);
    }
}
