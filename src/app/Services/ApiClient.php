<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ApiClient
{

    public static function getJsonArray(string $url, array $queryParams, float $delay = 0.0)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url, ['query' => $queryParams, 'delay' => $delay]);
            return json_decode($res->getBody(), true);
        } catch (ClientException $e) {
            //FIXME: error handling
            throw $e;
        }
    }
}
