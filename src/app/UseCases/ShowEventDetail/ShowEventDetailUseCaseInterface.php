<?php


namespace App\UseCases\ShowEventDetail;

interface ShowEventDetailUseCaseInterface
{
    public function handle(ShowEventDetailInputData $input): ShowEventDetailOutputData;
}
