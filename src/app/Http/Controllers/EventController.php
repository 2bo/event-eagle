<?php

namespace App\Http\Controllers;

use App\UseCases\SearchEvents\SearchEventsInputData;
use App\UseCases\SearchEvents\SearchEventsUseCaseInterface;
use Illuminate\Http\Request;
use DateTime;

class EventController extends Controller
{

    public function search(Request $request, SearchEventsUseCaseInterface $useCase)
    {
        $from = $request->query('from', null);
        $to = $request->query('to', null);
        $keywords = $request->query('keywords', '');
        $types = $request->query('types', []);
        $places = $request->query('places', []);
        $page = $request->query('page', 1);

        $fromDate = is_null($from) ? null : new DateTime($from);
        $toDate = is_null($to) ? null : new DateTime($to);

        $isOnline = null;
        //place=0はオンラインとみなす
        $index = array_search(0, $places);
        if ($index !== false) {
            $isOnline = true;
            unset($places[$index]);
        }

        $input = new SearchEventsInputData($fromDate, $toDate, $keywords, $places, $types, $isOnline, $page);
        $output = $useCase->handle($input);
        return response()->json($output->getPaginateResult()->toArray());
    }
}
