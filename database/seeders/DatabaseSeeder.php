<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        /*DB::table('users')->insert(
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make(123),
                'name' => 'Admin Supper',
                'phone' => '0377708868',
                'email_verified_at' => Carbon::now()
            ]
        );*/
        DB::table('owner_china')->insert([
            [
                'oc_name' => 'Bảo',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Quân',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Đức',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Thanh',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Hải',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Chen',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Kim',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Cường',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Cẩm Anh',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Sinh',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Đông',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ],
            [
                'oc_name' => 'Béo',
                'oc_total_money' => 0,
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
