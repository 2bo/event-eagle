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
            [1, 'LT', '((LT)|(ライトニングトーク)|(lightning[\s-]{0,1}talk[s]{0,1}))'],
            [2, 'もくもく会', '((もくもく[会]{0,1})|(mokumoku))'],
            [3, 'ハンズオン', '((ハンズオン)|(hands[\s-]{0,1}on))'],
            [4, 'ハッカソン', '((ハッカソン)|(hackathon))'],
            [5, 'セミナー', '((セミナー)|(seminar))'],
            [6, 'カンファレンス', '((カンファレンス)|(conference))'],
            [7, 'ワークショップ', '((ワークショップ)|(workshop))'],
            [8, '輪読会', '((輪読会)|(読書会)|(reading[\s-]{0,1}circle))'],
        ];

        foreach ($eventTypes as $eventType) {
            EventType::updateOrCreate([
                'id' => $eventType[0],
                'name' => $eventType[1],
                'needle' => $eventType[2]
            ]);
        }
    }
}
