<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\apiClient;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class fetchConnpassEventsFromAPI
 * @package App\Console\Commands
 * https://connpass.com/about/api/
 */
class fetchConnpassEventsFromAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:connpass 
    {--e|event_id=* : event id}
    {--k|keyword=* : keyword}
    {--m|ym=* : year month}
    {--d|ymd=* : year month day} 
    {--pn|nickname=* : participant nickname }
    {--on|owner_nickname=* : owner nickname }
    {--i|series_id=* : group id}
    {--s|start=1 : the page number of search results} 
    {--o|order=3 : order of search result(1:updated_at, 2:started_at, 3:created_at)}
    {--c|count=100 :  the maximum number of output data}
    {--a|all=1 : paging to get all records(0:false, 1:true) }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch connpass events from api and store to DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = config('const.connpass_api_url');
        $jsonArray = apiClient::getJsonArray($url, $this->getUrlQueryParamFromOptions());
        Event::updateOrCreateFromConnpassJson($jsonArray);

        //全件取得する
        if ($this->option('all')) {
            $count = $this->option('count');
            $start = $this->option('start');
            $eventCount = $jsonArray['results_available'];
            //start位置から最後まで取得するための繰り返し回数
            $loopCount = intdiv($eventCount, $count);
            if ($eventCount % $count !== 0) $loopCount++;

            for ($i = 0; $i < $loopCount; $i++) {
                $jsonArray = apiClient::getJsonArray($url, $this->getUrlQueryParamFromOptions($start));
                Event::updateOrCreateFromConnpassJson($jsonArray);
                $start += $count;
            }
        }
    }

    private function getUrlQueryParamFromOptions(int $start = null)
    {
        // urlパラメータに使用するオプションのキー
        $options = $this->options();
        // 除外キー
        $excludeKeys = ['all'];

        foreach ($excludeKeys as $key) {
            unset($options[$key]);
        }

        $queryParams = [];
        //startが引数で指定されている場合上書きする
        if ($start) $options['start'] = $start;

        foreach ($options as $key => $value) {
            if ($options[$key] && $options[$key] != 0) {
                if (is_array($value)) {
                    $queryParams[$key] = implode(',', $value);
                } else {
                    $queryParams[$key] = $value;
                }
            }
        }

        //日付の指定がない場合、今月を基準に既定範囲で取得する
        if (!$options['ym'] && !$options['ymd']) {
            //範囲
            $monthFrom = 1;//ヶ月前~
            $monthTo = 3; //~ヶ月後

            $from = Carbon::today()->subMonth($monthFrom);
            $ym = [];
            for ($i = 0; $i <= ($monthFrom + $monthTo); $i++) {
                $ym[] = $from->format('Ym');
                $from->addMonth(1);
            }
            $queryParams['ym'] = implode(',', $ym);
        }

        return $queryParams;
    }
}
