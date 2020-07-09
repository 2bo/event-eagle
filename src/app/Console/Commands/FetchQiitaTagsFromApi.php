<?php

namespace App\Console\Commands;

use App\Services\QiitaService;
use Exception;
use Illuminate\Console\Command;

class FetchQiitaTagsFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch Qiita tags from api';

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
        try {
            QiitaService::fetchTagsFromApi(1, 10);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
