<?php


namespace App\Services;

use App\Models\Prefecture;
use App\ApiClients\ApiClient;

class PrefectureService
{

    //FIXME: 非staticに変更
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

    //FIXME: 非staticに変更
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

    //FIXME バグっていそうなので要確認
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
