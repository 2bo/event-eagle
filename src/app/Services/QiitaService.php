<?php


namespace App\Services;

use App\Repositories\TagRepository;
use App\ApiClients\ApiClient;

class QiitaService
{

    const QIITA_HOST = 'https://qiita.com';
    const API_PATHS = ['tags' => '/api/v2/tags',];

    public static function fetchTagsFromApi(int $startPage = 1, int $endPage = 10)
    {

        $url = self::QIITA_HOST . self::API_PATHS['tags'];
        $params = ['per_page' => '100', 'sort' => 'count'];
        $tagRepository = app(TagRepository::class);

        for ($i = $startPage; $i <= $endPage; $i++) {
            $params['page'] = $i;
            $jsonArray = ApiClient::getJsonArray($url, $params, 60000);
            $tagNames = array_column($jsonArray, 'id');
            $tagRepository->saveTagsFromNames($tagNames);
        }
    }
}

