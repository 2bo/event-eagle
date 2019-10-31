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
}
