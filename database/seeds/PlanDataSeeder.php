<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PlanDataSeeder extends Seeder
{
    /**
     * データベース初期値設定実行
     *
     * @return void
     */
    public function run()
    {
        DB::table('plan_data')->delete();

        $datetime = Carbon::now();

        $services = [
            [
                'id'                => 1,
                'place_id'          => 2,
                'start'             => '2020-05-07 22:00:00',
                'end'               => '2020-05-07 22:00:00',
                'enable'            => 1,
                'description'       => '20時～22時 杜の宮グラウンド',
                'created_at'        => $datetime,
                'updated_at'        => $datetime,
            ], [
                'id'                => 2,
                'place_id'          => 1,
                'start'             => '2020-05-14 19:00:00',
                'end'               => '2020-05-14 21:00:00',
                'enable'            => 1,
                'description'       => '19時～21時 新宮東小グラウンド',
                'created_at'        => $datetime,
                'updated_at'        => $datetime,
            ], [
                'id'                => 3,
                'place_id'          => 1,
                'start'             => '2020-05-21 20:00:00',
                'end'               => '2020-05-21 22:00:00',
                'enable'            => 1,
                'description'       => '20時～22時 杜の宮グラウンド',
                'created_at'        => $datetime,
                'updated_at'        => $datetime,
            ], [
                'id'                => 4,
                'place_id'          => 2,
                'start'             => '2020-05-28 19:00:00',
                'end'               => '2020-05-28 21:00:00',
                'enable'            => 1,
                'description'       => '19時～21時 新宮東小グラウンド',
                'created_at'        => $datetime,
                'updated_at'        => $datetime,
            ]
        ];

        DB::table('plan_data')->insert($services);
    }
}
