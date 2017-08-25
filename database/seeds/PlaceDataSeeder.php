<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PlaceDataSeeder extends Seeder
{
    /**
     * データベース初期値設定実行
     *
     * @return void
     */
    public function run()
    {
        DB::table('place_data')->delete();

        $datetime = Carbon::now();

        $services = [
            [
                'id'                => 1,
                'name'              => '新宮東小学校グラウンド',
                'description'       => '新宮東小学校グラウンドの詳細',
                'map'               => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3319.129735001883!2d130.44957681562428!3d33.70559174336462!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x354188f4d42f2a35%3A0x48ca65c5216fcb27!2z5paw5a6u55S656uL5paw5a6u5p2x5bCP5a2m5qCh!5e0!3m2!1sja!2sjp!4v1458088676425" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>',
                'created_at'        => $datetime,
                'updated_at'        => $datetime,
            ],[
                'id'                => 2,
                'name'              => '杜の宮グラウンド',
                'description'       => '杜の宮グラウンドの詳細',
                'map'               => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3318.7782009772022!2d130.43741831562426!3d33.71468774288935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35418857a59e5311%3A0x74989ebf5ae4a046!2z5p2c44Gu5a6u44Kw44Op44Km44Oz44OJ!5e0!3m2!1sja!2sjp!4v1458088828596" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>',
                'created_at'        => $datetime,
                'updated_at'        => $datetime,
            ],[
                'id'                => 3,
                'name'              => '新宮中学校グラウンド',
                'description'       => '新宮中学校グラウンドの詳細',
                'map'               => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3318.94781035548!2d130.44202611562426!3d33.71029934311854!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x354188f632678e1d%3A0x106c7745f378260!2z5paw5a6u55S656uL5paw5a6u5Lit5a2m5qCh!5e0!3m2!1sja!2sjp!4v1458088806982" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>',
                'created_at'        => $datetime,
                'updated_at'        => $datetime,
            ]
        ];

        DB::table('place_data')->insert($services);
    }
}
