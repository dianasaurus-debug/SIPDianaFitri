<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'nomor_hp' => '083172819999',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 1
        ]);
        User::create([
            'name' => 'AdminAja',
            'username' => 'admin2',
            'nomor_hp' => '083172810000',
            'email' => 'adminaja@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 1
        ]);
        User::create([
            'name' => 'Mimin',
            'username' => 'admin3',
            'nomor_hp' => '0831728111111',
            'email' => 'mimin@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 1
        ]);

        User::create([
            'name' => 'Diana',
            'username' => 'diana99',
            'nomor_hp' => '082272819999',
            'email' => 'diana@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2
        ]);
        User::create([
            'name' => 'Fitri',
            'username' => 'fitri11',
            'nomor_hp' => '083177719999',
            'email' => 'fitri@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'Maimunah',
            'username' => 'maimun99',
            'nomor_hp' => '083272819999',
            'email' => 'maimunah@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2
        ]);
        User::create([
            'name' => 'Rosyidi',
            'username' => 'rosyidiii',
            'nomor_hp' => '082172819999',
            'email' => 'rosyidi@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2
        ]);
        User::create([
            'name' => 'Imron',
            'username' => 'imrannnn',
            'nomor_hp' => '082172919999',
            'email' => 'imron@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2
        ]);
        User::create([
            'name' => 'Talita',
            'username' => 'boboho',
            'nomor_hp' => '081172819999',
            'email' => 'talita@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2
        ]);
        User::create([
            'name' => 'Nada',
            'username' => 'tone',
            'nomor_hp' => '083192819999',
            'email' => 'nada@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2
        ]);
        User::create([
            'name' => 'Jihan',
            'username' => 'hijun',
            'nomor_hp' => '081172839999',
            'email' => 'jihan@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2
        ]);

    }
}
