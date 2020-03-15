<?php

namespace App\Domain\Services;

use App\ApiClients\ApiClient;
use App\Domain\Models\Prefecture\PrefectureRepositoryInterface;
use App\Domain\Models\Prefecture\Prefecture;

class PrefectureService
{
    private $prefectureRepository;

    public function __construct(PrefectureRepositoryInterface $prefectureRepository)
    {
        $this->prefectureRepository = $prefectureRepository;
    }

    private function getPrefectureFromAddress(string $address = ''): ?Prefecture
    {
        $prefectures = $this->prefectureRepository->findAll();
        foreach ($prefectures as $prefecture) {
            if (mb_strpos($address, $prefecture->getName()) !== false) {
                return $prefecture;
            }
        }
        return null;
    }

    public function getPrefectureFromCoordinates(float $lat, float $lon): ?Prefecture
    {
        $url = config('const.geoapi_url');
        $params = [
            'method' => 'searchByGeoLocation',
            'x' => $lon,
            'y' => $lat,
        ];
        $jsonArray = ApiClient::getJsonArray($url, $params, 5000.0);

        if (isset($jsonArray['response']['error'])) {
            return null;
        }

        //1件目のデータの都道府県名
        $firstPrefectureName = $jsonArray['response']['location'][0]['prefecture'];
        $prefecture = $this->prefectureRepository->findByName($firstPrefectureName);
        return $prefecture;
    }

    public function getPrefecture(?string $address = null, ?float $lat = null, ?float $lon = null): ?Prefecture
    {
        $prefecture = null;
        if ($address) {
            $prefecture = self::getPrefectureFromAddress($address);
        }
        if (is_null($prefecture) && $lat && $lon) {
            $prefecture = self::getPrefectureFromCoordinates($lat, $lon);
        }
        return $prefecture;
    }
}
