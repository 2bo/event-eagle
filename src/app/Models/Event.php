<?php

namespace App\Models;

use App\Services\PrefectureService;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $guarded = [
        'id'
    ];

    public static function updateOrCreateFromConnpassJson(array $connpassJsonArray)
    {
        foreach ($connpassJsonArray['events'] as $event) {

            $prefecture = $event['address'] ? PrefectureService::getPrefectureFromAddress($event['address']) : null;
            if (!$prefecture && !empty($event['lat']) && !empty($event['lon'])) {
                $prefecture = PrefectureService::getPrefectureFromCoordinates($event['lat'], $event['lon']);
            }

            Event::updateOrCreate(
                [
                    'site_name' => 'connpass',
                    'event_id' => $event['event_id']
                ],
                [
                    'site_name' => 'connpass',
                    'title' => $event['title'],
                    'catch' => $event['catch'],
                    'description' => $event['description'],
                    'event_url' => $event['event_url'],
                    'prefecture_id' => $prefecture ? $prefecture->id : null,
                    'address' => $event['address'],
                    'place' => $event['place'],
                    'lat' => $event['lat'],
                    'lon' => $event['lon'],
                    'started_at' => Carbon::create(($event['started_at']))->format('Y-m-d H:i:s'),
                    'ended_at' => Carbon::create(($event['ended_at']))->format('Y-m-d H:i:s'),
                    'limit' => $event['limit'],
                    'participants' => $event['accepted'],
                    'waiting' => $event['waiting'],
                    'owner_id' => $event['owner_id'],
                    'owner_nickname' => $event['owner_nickname'],
                    'owner_display_name' => $event['owner_display_name'],
                    'group_id' => $event['series']['id'],
                    'event_updated_at' => Carbon::create(($event['updated_at']))->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
