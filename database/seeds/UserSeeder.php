<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * データベース初期値設定実行
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $datetime = Carbon::now();

        $services = [
            [
                'name'          => '田中一郎',
                'email'         => 'admin@demo.com',
                'password'      => bcrypt('admin'),
                'is_admin'      => 1,
                'created_at'    => $datetime,
                'updated_at'    => $datetime,
            ],
            [
                'name'          => '鈴木次郎',
                'email'         => 'user@demo.com',
                'password'      => bcrypt('user'),
                'is_admin'      => 0,
                'created_at'    => $datetime,
                'updated_at'    => $datetime,
            ],
        ];
        DB::table('users')->insert($services);
    }
}
