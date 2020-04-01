<?php

use Illuminate\Database\Seeder;
use App\DataModels\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eventTypes = [
            [1, 'LT', '((LT)|(ライトニングトーク)|([Ll][Ii][Gg][Hh][Tt][Nn][Ii][Nn][Gg][\s-]{0,1}[Tt][Aa][Ll][Kk][Ss]{0,1}))'],
            [2, 'もくもく会', '((もくもく[会]{0,1})|([[Mm][Oo][Kk][Uu][Mm][Oo][Kk][Uu]))'],
            [3, 'ハンズオン', '((ハンズオン)|([Hh][Aa][Nn][Dd][Ss][\s-]{0,1}[Oo][Nn]))'],
            [4, 'ハッカソン', '((ハッカソン)|([Hh][Aa][Cc][Kk][Aa][Tt][Hh][Oo][Nn]))'],
            [5, 'セミナー', '((セミナー)|([Ss][Ee][Mm][Ii][Nn][Aa][Rr]))'],
            [6, 'カンファレンス', '((カンファレンス)|([Cc][Oo][Nn][Ff][Ee][Rr][Ee][Nn][Cc][Ee]))'],
            [7, 'ワークショップ', '((ワークショップ)|([Ww][Oo][Rr][Kk][Ss][Hh][Oo][Pp]))'],
            [8, '輪読会', '((輪読会)|(読書会)|([Rr][Ee][Aa][Dd][Ii][Nn][Gg][\s-]{0,1}[Cc][Ii][Rr][Cc][Ll][Ee]))'],
        ];

        foreach ($eventTypes as $eventType) {
            EventType::updateOrCreate(
                [
                    'id' => $eventType[0]
                ],
                [
                    'name' => $eventType[1],
                    'needle' => $eventType[2]
                ]
            );
        }
    }
}
