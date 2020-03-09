<?php

namespace Tests\Unit;

use App\Domain\Event\Event;
use App\Services\PrefectureService;
use Tests\TestCase;

class PrefectureServiceTest extends TestCase
{
    static private $prefectureService;

    public static function setUpBeforeClass()
    {
        self::$prefectureService = new PrefectureService();
    }

    function testGetPrefecture()
    {
        //住所に含まれる都道府県を取得
        $prefecture = self::$prefectureService->getPrefecture('愛知県名古屋市');
        self::assertEquals(23, $prefecture->id);
        self::assertEquals('愛知県', $prefecture->name);

        //住所に都道府県名がない場合、緯度経度から都道府県を取得
        $prefecture = self::$prefectureService->getPrefecture('西池袋', 35.729730300000, 139.708040200000);
        self::assertEquals(13, $prefecture->id);
    }

}
