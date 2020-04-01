<?php

namespace Tests\Unit\Services;

use App\Domain\Services\PrefectureService;
use App\Repositories\PrefectureRepository;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PrefectureServiceTest extends TestCase
{
    static private $prefectureService;

    static private $isDbInitialized = false;

    public function setUp(): void
    {
        parent::setUp();
        if (!self::$isDbInitialized) {
            self::initializeDb();
        }
    }

    private static function initializeDb()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed --class=PrefectureSeeder');
        self::$prefectureService = new PrefectureService(new PrefectureRepository());
        self::$isDbInitialized = true;
    }


    function testGetPrefecture()
    {
        //住所に含まれる都道府県を取得
        $prefecture = self::$prefectureService->getPrefecture('愛知県名古屋市');
        self::assertEquals(23, $prefecture->getId()->value());
        self::assertEquals('愛知県', $prefecture->getName());

        //住所に都道府県名がない場合、緯度経度から都道府県を取得
        $prefecture = self::$prefectureService->getPrefecture('西池袋', 35.729730300000, 139.708040200000);
        self::assertEquals(13, $prefecture->getId()->value());
    }

}
