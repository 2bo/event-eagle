<?php

namespace App\Http\Controllers;

use App\UseCases\SearchEvents\SearchEventsInputData;
use App\UseCases\SearchEvents\SearchEventsUseCaseInterface;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function search(Request $request, SearchEventsUseCaseInterface $useCase)
    {
        $keywords = $request->query('keywords', '');
        $types = $request->query('types', []);
        $places = $request->query('places', []);
        $page = $request->query('page', 1);

        $isOnline = null;
        //place=0はオンラインとみなす
        $index = array_search(0, $places);
        if ($index !== false) {
            $isOnline = true;
            unset($places[$index]);
        }

        $input = new SearchEventsInputData($keywords, $places, $types, $isOnline, $page);
        $output = $useCase->handle($input);
        return response()->json($output->getPaginateResult()->toArray());
    }
}
