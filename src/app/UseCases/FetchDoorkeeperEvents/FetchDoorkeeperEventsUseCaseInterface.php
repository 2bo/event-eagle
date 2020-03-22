<?php


namespace App\UseCases\FetchDoorkeeperEvents;


interface FetchDoorkeeperEventsUseCaseInterface
{
    public function handle(FetchDoorkeeperEventsInputData $input): FetchDoorkeeperEventsOutputData;

}
