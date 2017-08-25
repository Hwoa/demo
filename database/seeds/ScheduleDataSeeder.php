<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleDataSeeder extends Seeder
{
    /**
     * データベース初期値設定実行
     *
     * @return void
     */
    public function run()
    {
        DB::table('schedule_data')->delete();

        $datetime = Carbon::now();

        $services = [

        ];

        DB::table('schedule_data')->insert($services);
    }
}
