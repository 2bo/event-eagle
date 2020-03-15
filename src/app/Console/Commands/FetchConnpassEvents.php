<?php

namespace App\Console\Commands;

use App\UseCases\FetchConnpassEvents\FetchConnpassEventsInputData;
use App\UseCases\FetchConnpassEvents\FetchConnpassEventsUseCaseInterface;
use Illuminate\Console\Command;

class FetchConnpassEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:connpass {year_month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch connpass events';

    private $fetchConnpassEventUseCase;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FetchConnpassEventsUseCaseInterface $fetchConnpassEventUseCase)
    {
        parent::__construct();
        $this->fetchConnpassEventUseCase = $fetchConnpassEventUseCase;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $yearMonth = $this->argument('year_month');
        $inputData = new FetchConnpassEventsInputData($yearMonth);
        $outputData = $this->fetchConnpassEventUseCase->handle($inputData);
        $this->info("fetched {$outputData->getNumOfEvents()} connpass events");
    }
}
