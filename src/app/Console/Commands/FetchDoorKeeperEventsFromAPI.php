<?php

namespace App\Console\Commands;

use App\Services\DoorkeeperService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FetchDoorKeeperEventsFromAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:doorkeeper
    {--p|page= : the page offset of the results }
    {--l|locale=ja : the localized text for an event }
    {--o|sort=starts_at : The order of the results(One of published_at, starts_at, updated_at) }
    {--s|since=-1month : Only events taking place during or after this date will be included (YYYYMMDD) }
    {--u|until= : Only events taking place during or before this date will be included (YYYYMMDD) }
    {--k|keyword= : Keyword to search for from the title or description fields }
    {--g|expand_group=0 : Expands the group object(0:false, 1:true) }
    {--a|all=1 : paging to get all records(0:false, 1:true) }';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch doorkeeper events from api and store to DB';

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
        $queryParams = $this->getQueryParamFromOptions();
        DoorkeeperService::fetchEventsFromAPI($queryParams, !!$this->option('all'));
    }

    private function getQueryParamFromOptions()
    {

        $options = $this->options();

        //除外オプション
        $excludeOptions = ['expand_group', 'all'];
        foreach ($excludeOptions as $excludeOption) {
            unset($options[$excludeOption]);
        }

        //オプションとパラメータの対応
        $optionToParam = [
            'page' => 'page',
            'locale' => 'locale',
            'sort' => 'sort',
            'since' => 'since',
            'until' => 'until',
            'keyword' => 'q',
            'expand_group' => 'expand[]',
        ];

        $params = [];
        foreach ($options as $option => $value) {
            if ($value) {
                $params[$optionToParam[$option]] = $value;
            }
        }

        if ($options['since']) {
            $jst = new Carbon($options['since']);
            $params['since'] = $jst->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');
        }

        if ($options['until']) {
            $jst = new Carbon($options['until']);
            $params['until'] = $jst->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z');
        }

        if ($this->option('expand_group')) {
            $params[$optionToParam['expand_group']] = 'group';
        }

        return $params;
    }
}
