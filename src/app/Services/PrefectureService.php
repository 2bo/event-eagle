<?php


namespace App\Services;

use App\Models\Prefecture;

class PrefectureService
{

    public static function getPrefectureFromAddress(string $address = ''): ?Prefecture
    {
        $prefectures = Prefecture::select(['id', 'name'])->get();
        foreach ($prefectures as $prefecture) {
            if (mb_strpos($address, $prefecture->name) !== false) {
                return $prefecture;
            }
        }
        return null;
    }

    public static function getPrefectureFromCoordinates(float $lat, float $lon): ?Prefecture
    {
        $url = config('const.geoapi_url');
        $params = [
            'method' => 'searchByGeoLocation',
            'x' => $lon,
            'y' => $lat,
        ];
        $jsonArray = ApiClient::getJsonArray($url, $params, 1000.0);

        if (isset($jsonArray['response']['error'])) {
            return null;
        }

        //1件目のデータの都道府県名
        $firstPrefectureName = $jsonArray['response']['location'][0]['prefecture'];
        $prefecture = Prefecture::where('name', $firstPrefectureName)->first();
        return $prefecture;
    }
}
