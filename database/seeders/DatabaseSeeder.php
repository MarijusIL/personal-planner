<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\Status;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('users')->insert([
            'name' => 'marijus',
            'email' => 'marijus@ilaras.lt',
            'password' => Hash::make('12345678'),
        ]);
        
        $statuses = ['completed', 'uncompleted', 'postponed'];
        foreach(range(0, count($statuses)-1) as $i) {
            DB::table('statuses')->insert([
                'name' => $statuses[$i],
            ]);
        }

        $statusList = [];
        foreach(Status::all() as $sts) {
            $statusList[] = $sts->id;
        }

        foreach(range(1, 200) as $_) {
            DB::table('tasks')->insert([
                'name' => $faker->bs,
                'description' => $faker->realText($maxNbChars = 50),
                'status_id' => $statusList[rand(0, count($statusList) - 1)],
                'completed_date' => '2021-09-25'
            ]);
        }
    }
}