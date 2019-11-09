<?php

use Illuminate\Database\Seeder;
use App\Models\Prefecture;

class PrefectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prefectures = [
            [1, '北海道', 'ホッカイドウ', 'hokkaido'],
            [2, '青森県', 'アオモリケン', 'aomori'],
            [3, '岩手県', 'イワテケン', 'iwate'],
            [4, '宮城県', 'ミヤギケン', 'miyagi'],
            [5, '秋田県', 'アキタケン', 'akita'],
            [6, '山形県', 'ヤマガタケン', 'yamagata'],
            [7, '福島県', 'フクシマケン', 'fukushima'],
            [8, '茨城県', 'イバラキケン', 'ibaraki'],
            [9, '栃木県', 'トチギケン', 'tochigi'],
            [10, '群馬県', 'グンマケン', 'gunma'],
            [11, '埼玉県', 'サイタマケン', 'saitama'],
            [12, '千葉県', 'チバケン', 'chiba'],
            [13, '東京都', 'トウキョウト', 'tokyo'],
            [14, '神奈川県', 'カナガワケン', 'kanagawa'],
            [15, '新潟県', 'ニイガタケン', 'niigata'],
            [16, '富山県', 'トヤマケン', 'toyama'],
            [17, '石川県', 'イシカワケン', 'ishikawa'],
            [18, '福井県', 'フクイケン', 'fukui'],
            [19, '山梨県', 'ヤマナシケン', 'yamanashi'],
            [20, '長野県', 'ナガノケン', 'nagano'],
            [21, '岐阜県', 'ギフケン', 'gifu'],
            [22, '静岡県', 'シズオカケン', 'shizuoka'],
            [23, '愛知県', 'アイチケン', 'aichi'],
            [24, '三重県', 'ミエケン', 'mie'],
            [25, '滋賀県', 'シガケン', 'shiga'],
            [26, '京都府', 'キョウトフ', 'kyoto'],
            [27, '大阪府', 'オオサカフ', 'osaka'],
            [28, '兵庫県', 'ヒョウゴケン', 'hyogo'],
            [29, '奈良県', 'ナラケン', 'nara'],
            [30, '和歌山県', 'ワカヤマケン', 'wakayama'],
            [31, '鳥取県', 'トットリケン', 'tottori'],
            [32, '島根県', 'シマネケン', 'shimane'],
            [33, '岡山県', 'オカヤマケン', 'okayama'],
            [34, '広島県', 'ヒロシマケン', 'hiroshima'],
            [35, '山口県', 'ヤマグチケン', 'yamaguchi'],
            [36, '徳島県', 'トクシマケン', 'tokushima'],
            [37, '香川県', 'カガワケン', 'kagawa'],
            [38, '愛媛県', 'エヒメケン', 'ehime'],
            [39, '高知県', 'コウチケン', 'kochi'],
            [40, '福岡県', 'フクオカケン', 'fukuoka'],
            [41, '佐賀県', 'サガケン', 'saga'],
            [42, '長崎県', 'ナガサキケン', 'nagasaki'],
            [43, '熊本県', 'クマモトケン', 'kumamoto'],
            [44, '大分県', 'オオイタケン', 'oita'],
            [45, '宮崎県', 'ミヤザキケン', 'miyazaki'],
            [46, '鹿児島県', 'カゴシマケン', 'kagoshima'],
            [47, '沖縄県', 'オキナワケン', 'okinawa']
        ];

        foreach ($prefectures as $prefecture) {
            Prefecture::updateOrCreate([
                'id' => $prefecture[0],
                'name' => $prefecture[1],
                'kana_name' => $prefecture[2],
                'english_name' => $prefecture[3]
            ]);
        }
    }
}
