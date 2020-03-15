<?php

namespace App\UseCases\FetchConnpassEvents;

interface FetchConnpassEventsUseCaseInterface
{
    public function handle(FetchConnpassEventsInputData $input);
}
