<?php

namespace Tests\Unit\Repositories;

use App\Domain\Models\Prefecture\Prefecture;
use App\Domain\Models\Prefecture\PrefectureId;
use App\Repositories\PrefectureRepository;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\DataModels\Prefecture as PrefectureDataModel;

class PrefectureRepositoryTest extends TestCase
{

    private static $prefectureRepository;
    private static $dbInitialized = false;

    public static function setUpBeforeClass()
    {
        self::$prefectureRepository = new PrefectureRepository();
    }

    protected function setUp(): void
    {
        parent::setUp();
        if (!self::$dbInitialized) {
            self::initializeDB();
        }
    }

    protected static function initializeDB(): void
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed --class PrefectureSeeder');
        self::$dbInitialized = true;
    }

    public function testSave()
    {
        PrefectureDataModel::find(1)->delete();

        $name = '北海道';
        $kanaName = 'ホッカイドウ';
        $englishName = 'Hokkaido';

        $prefectureId = new PrefectureId(1);
        $prefecture = new Prefecture($prefectureId, $name, $kanaName, $englishName);
        self::$prefectureRepository->save($prefecture);

        $prefecture = self::$prefectureRepository->findById($prefectureId);
        self::assertEquals($name, $prefecture->getName());
        self::assertEquals($kanaName, $prefecture->getKanaName());
        self::assertEquals($englishName, $prefecture->getEnglishName());
    }

    public function testFindAll()
    {
        $prefectures = self::$prefectureRepository->findAll();
        self::assertEquals(47, count($prefectures));
    }

    public function testFindById()
    {
        $prefectureId = new PrefectureId(13);
        $prefecture = self::$prefectureRepository->findById($prefectureId);
        self::assertEquals('東京都', $prefecture->getName());
    }

    public function testFindByName()
    {
        $prefecture = self::$prefectureRepository->findByName('東京都');
        self::assertEquals('東京都', $prefecture->getName());
    }
}
