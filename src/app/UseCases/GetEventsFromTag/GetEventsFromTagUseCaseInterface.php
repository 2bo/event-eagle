<?php


namespace App\UseCases\GetEventsFromTag;


interface GetEventsFromTagUseCaseInterface
{
    public function handle(GetEventsFromTagInputData $input): GetEventsFromTagOutputData;
}
