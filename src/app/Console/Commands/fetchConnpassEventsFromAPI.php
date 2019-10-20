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
    protected $signature = 'fetch:connpass {--e|event_id=*} {--k|keyword=*} {--y|ym=*} {--Y|ymd=*} 
    {--nn|nickname=*} {--i|series_id=*} {--s|start=1} {--o|order=1} {--c|count=100} {--a|all=1}';

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
        $connpassJson = apiClient::getJsonArray($url, $this->getUrlQueryParamFromOptions());
$this->updateOrCreateEvents($connpassJson['events']);

        //全件取得する
        if ($this->option('all')) {
            $count = $this->option('count');
            $start = $this->option('start');
            $eventCount = $connpassJson['results_available'];
            //start位置から最後まで取得するための繰り返し回数
            $loopCount = intdiv($eventCount - ($start * $count), $count);

            var_dump([$count, $start, $eventCount,$loopCount]);
            for ($i = 0; $i < $loopCount; $i++) {
                $connpassJson = apiClient::getJsonArray($url, $this->getUrlQueryParamFromOptions(++$start));
                $this->updateOrCreateEvents($connpassJson['events']);
            }
        }
    }

    private function getUrlQueryParamFromOptions(int $start = null)
    {
        $queryParams = [];
        // urlパラメータに使用するオプションのキー
        $targetKey = ['event_id', 'keyword', 'ym', 'ymd', 'nickname', 'series_id', 'start', 'order', 'count'];
        $options = array_intersect_key($this->options(), array_flip($targetKey));

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
        return $queryParams;
    }

    private function updateOrCreateEvents(array $events)
    {
        foreach ($events as $event) {
            Event::updateOrCreate(
                [
                    'site_name' => 'connpass',
                    'event_id' => $event['event_id']
                ],
                [
                    'site_name' => 'connpass',
                    'title' => $event['title'],
                    'catch' => $event['catch'],
                    'description' => $event['description'],
                    'event_url' => $event['event_url'],
                    'address' => $event['address'],
                    'place' => $event['place'],
                    'lat' => $event['lat'],
                    'lon' => $event['lon'],
                    'started_at' => Carbon::create(($event['started_at']))->format('Y-m-d H:i:s'),
                    'ended_at' => Carbon::create(($event['ended_at']))->format('Y-m-d H:i:s'),
                    'limit' => $event['limit'],
                    'participants' => $event['accepted'],
                    'waiting' => $event['waiting'],
                    'owner_id' => $event['owner_id'],
                    'owner_nickname' => $event['owner_nickname'],
                    'owner_display_name' => $event['owner_display_name'],
                    'group_id' => $event['series']['id'],
                    'event_updated_at' => Carbon::create(($event['updated_at']))->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
