<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $me = $users->first();
        $me_id = $me->id;

        $followers = $users->slice(1);
        $followersIds = $followers->pluck('id')->toArray();

        //关注除了第一个用户外的所有用户
        $me->follow($followersIds);

        //除了第一个用户的所有用户关注第一个用户
        foreach ($followers as $follower) {
            $follower->follow($me_id);
        }
    }
}
