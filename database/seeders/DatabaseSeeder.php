<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::create([
             'name'=>'Abdul Rafay',
             'phone'=>'03147938798',
             'login_id'=>'rafay789',
             'image'=>'https://ui-avatars.com/api/?name=Abdul+Rafay',
             'status'=>"Available"
         ]);

         \App\Models\User::create([
            'name'=>'Ahmed Fattouh',
            'phone'=>'123123123',
            'login_id'=>'tintnhue',
            'image'=>'https://ui-avatars.com/api/?name=Ahmed+Fattouh',
            'status'=>"Available"

        ]);
        \DB::table('roles')->insert([
            ['name'=>'Manager'],
            ['name'=>'Collector'],
            ['name'=>'Office'],
            ['name'=>'Driver']
        ]);
        
        \DB::table('role_user')->insert([
        ['role_id' => '1','user_id' => 1],
        ['role_id' => '2','user_id' => 1],
        ['role_id' => '3','user_id' => 1],
        ['role_id' => '4','user_id' => 1],

        ['role_id' => '4', 'user_id' => 2]
        ]);


        \App\Models\Vehicles::create([
            'name'=>'Ford',
            'image'=>'https://ui-avatars.com/api/?name=Ford+GT',
            'status'=>"Available"

        ]);
    }
}
