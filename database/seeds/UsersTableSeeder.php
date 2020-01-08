<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

    	foreach (range(1,100) as $index) {
            $contract_start_date = dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = null);
	        DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt("password$index"), // $faker->password
                'contract_start_date' => $contract_start_date,
                'contract_end_date' =>  dateTimeBetween($startDate = $contract_start_date, $endDate = '+5 years', $timezone = null),
                'type' => $faker->randomElement(['normal', 'premium']),
                'verified' => $faker->randomElement([0, 1]),
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime
	        ]);
	    }
    }
}