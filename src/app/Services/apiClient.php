<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class apiClient
{

    public static function getJsonArray(string $url, array $queryParams)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url, ['query' => $queryParams]);
            return json_decode($res->getBody(), true);
        } catch (ClientException $e) {
            //FIXME: error handling
            throw $e;
        }
    }
}
