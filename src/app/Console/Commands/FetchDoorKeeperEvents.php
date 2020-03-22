<?php

namespace App\Console\Commands;

use App\Services\DoorkeeperService;
use App\UseCases\FetchDoorkeeperEvents\FetchDoorkeeperEventsInputData;
use App\UseCases\FetchDoorkeeperEvents\FetchDoorkeeperEventsUseCaseInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FetchDoorKeeperEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:doorkeeper {year_month : yyyymm}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch doorkeeper events';

    private $useCase;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FetchDoorkeeperEventsUseCaseInterface $useCase)
    {
        parent::__construct();
        $this->useCase = $useCase;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $yearMonth = $this->argument('year_month');
        $startOfMonth = sprintf('%0d-%0d-01', substr($yearMonth, 0, 4), substr($yearMonth, 4, 2));
        $since = Carbon::create($startOfMonth);
        $until = Carbon::create($startOfMonth)->endOfMonth();
        $input = new FetchDoorkeeperEventsInputData($since, $until);
        $output = $this->useCase->handle($input);
        $this->info($output->getNumOfEvents());
    }

}
