<?php

namespace App\Http\Controllers;

use App\UseCases\GetEventTypeConditions\GetEventTypeConditionsUseCaseInterface;
use App\UseCases\GetPlaceConditions\GetPlaceConditionsUseCaseInterface;
use Illuminate\Http\Request;

class SearchConditionController extends Controller
{
    public function getPlaceConditions(GetPlaceConditionsUseCaseInterface $useCase)
    {
       $output = $useCase->handle();
       return response()->json($output->getPlaceConditions());
    }

    public function getEventTypeConditions(GetEventTypeConditionsUseCaseInterface $useCase)
    {
        $output = $useCase->handle();
        return response()->json($output->getEventTypeConditions());
    }
}
