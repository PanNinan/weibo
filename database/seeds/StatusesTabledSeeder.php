<?php

use Illuminate\Database\Seeder;

class StatusesTabledSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = \App\Models\User::pluck('id');
        $faker = app(Faker\Generator::class);
        $statuses = factory(\App\Models\Status::class)
            ->times(150)
            ->make()
            ->each(function ($ststus) use (
                $faker,
                $user_ids
            ) {
                $ststus->user_id = $faker->randomElement($user_ids);
            });

        \App\Models\Status::insert($statuses->toArray());
    }
}
