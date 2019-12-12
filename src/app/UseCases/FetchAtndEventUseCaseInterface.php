<?php


namespace App\UseCases;


use App\InputPorts\FetchAtndEventsInput;

interface FetchAtndEventUseCaseInterface
{
    public function handle(FetchAtndEventsInput $input);
}
