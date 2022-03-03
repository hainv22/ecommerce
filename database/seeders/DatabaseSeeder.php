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
        DB::table('users')->insert(
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make(123),
                'name' => 'Admin Supper',
                'phone' => '0377708868',
                'email_verified_at' => Carbon::now()
            ]
        );
    }
}
