<?php

namespace App\ApiClients;

use Illuminate\Support\Facades\Log;

class AtndApiClient
{
    private const EVENT_API_URL = 'http://api.atnd.org/events';

    private $params = [
        'start' => '1',
        'count' => '100',
        'format' => 'json'
    ];

    public function fetchEvents()
    {
        Log::debug($this->params);
        return ApiClient::getJsonArray(self::EVENT_API_URL, $this->params, 5000);
    }

    private function setParameter(string $key, string $value)
    {
        $this->params[$key] = $value;
    }

    public function setYearMonthParameter(string $yyyymm)
    {
        $this->setParameter('ym', $yyyymm);
    }

    public function setStartParameter(string $start)
    {
        $this->setParameter('start', $start);
    }

    public function setCountParameter(string $count)
    {
        $this->setParameter('count', $count);
    }

    public function setFormatParameter(string $format)
    {
        $this->setParameter('format', $format);
    }

    public function setNextPage(int $page = 0)
    {
        $this->setParameter('start',$this->params['count'] * $page + 1);
    }
}
