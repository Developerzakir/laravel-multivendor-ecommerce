<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

            //admin
           [
            'name' =>'Admin',
            'username' =>'admin',
            'email' =>'admin@gmail.com',
            'password' =>Hash::make('12345678'),
            'role' =>'admin',
            'status' =>'active',
           ],

             //vendor
             [
                'name' =>'Riad Vendor',
                'username' =>'vendor',
                'email' =>'vendor@gmail.com',
                'password' =>Hash::make('12345678'),
                'role' =>'vendor',
                'status' =>'active',
            ],

              //vendor
              [
                'name' =>'User',
                'username' =>'user',
                'email' =>'user@gmail.com',
                'password' =>Hash::make('12345678'),
                'role' =>'user',
                'status' =>'active',
            ],

        ]);
    }
}
