<?php

namespace App\Http\Controllers;

use App\UseCases\SearchEvents\SearchEventsInputData;
use App\UseCases\SearchEvents\SearchEventsUseCaseInterface;
use App\UseCases\ShowEventDetail\ShowEventDetailInputData;
use App\UseCases\ShowEventDetail\ShowEventDetailUseCaseInterface;
use DateTime;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function show($id, ShowEventDetailUseCaseInterface $useCase)
    {
        $input = new ShowEventDetailInputData($id);
        $output = $useCase->handle($input);
        if (is_null($output->getEvent())) {
            return response()->json([], 404);
        }
        // TODO: add test code for json structure
        return response()->json($output->getEvent()->toArray());
    }

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
