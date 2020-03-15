<?php

namespace App\ApiClients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class ApiClient
{

    public static function getJsonArray(string $url, array $queryParams, float $delay = 0.0, array $headers = [])
    {
        try {
            $client = new Client();
            $res = $client->request('GET', $url, ['query' => $queryParams, 'delay' => $delay, 'headers' => $headers]);
            return json_decode($res->getBody(), true);
        } catch (ClientException $e) {
            //FIXME: error handling
            throw $e;
        }
    }

    public function get(string $url, array $queryParams = [], float $delay = 0.0, array $headers = [])
    {
        try {
            $client = new Client();
            $res = $client->get($url, [
                'query' => $queryParams,
                'delay' => $delay,
                'headers' => $headers
            ]);
            return $res->getBody()->getContents();
        } catch (ClientException $e) {
            Log::error($e);
            throw $e;
        }
    }
}
