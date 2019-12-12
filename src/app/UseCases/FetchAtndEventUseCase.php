<?php

namespace App\UseCases;

use App\ApiClients\AtndApiClient;
use App\InputPorts\FetchAtndEventsInput;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FetchAtndEventUseCase implements FetchAtndEventUseCaseInterface
{
    private $eventRepository;
    private $atndApiClient;

    public function __construct(EventRepository $eventRepository, AtndApiClient $atndApiClient)
    {
        $this->eventRepository = $eventRepository;
        $this->atndApiClient = $atndApiClient;
    }

    public function handle(FetchAtndEventsInput $input)
    {
        $startMonth = Carbon::createFromFormat('Ym', $input->startYearMonth);
        $endMonth = Carbon::createFromFormat('Ym', $input->endYearMonth);
        $diffInMonth = $startMonth->diffInMonths($endMonth);

        for ($i = 0; $i <= $diffInMonth; $i++) {
            $this->atndApiClient->setYearMonthParameter($startMonth->format('Ym'));
            $this->atndApiClient->setNextPage(0);
            $result = $this->atndApiClient->fetchEvents();
            $this->eventRepository->updateOCreateAtndEventsFromAPIResult($result['events']);
            $count = count($result['events']);

            for ($j = 1; $input->isAll && $count > 0; $j++) {
                $this->atndApiClient->setNextPage($j);
                $result = $this->atndApiClient->fetchEvents();
                $this->eventRepository->updateOCreateAtndEventsFromAPIResult($result['events']);
                Log::debug($count);
                $count = count($result['events']);
            }
            $startMonth->addMonths(1);
        }
    }
}
