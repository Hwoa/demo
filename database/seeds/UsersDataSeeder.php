<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersDataSeeder extends Seeder
{
    /**
     * データベース初期値設定実行
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_data')->delete();

        $datetime = Carbon::now();

        $services = [
          
        ];

        DB::table('users_data')->insert($services);
    }
}
