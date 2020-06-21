<?php


namespace App\UseCases\SearchEvents;


interface SearchEventsUseCaseInterface
{
    public function handle(SearchEventsInputData $input): SearchEventsOutputData;
}
