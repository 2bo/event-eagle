<?php

namespace App\Console\Commands;

use App\Models\Workshop;
use App\Services\apiClient;
use Carbon\Carbon;
use Illuminate\Console\Command;

class updateConnpassWorkshops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:connpass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update connpass workshops';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = "https://connpass.com/ap/v1/event/";
        $queryParams = ['count' => '100', 'order' => '3'];
        $connpassJson = apiClient::getJsonArray($url, $queryParams);

        foreach ($connpassJson['events'] as $workshop) {
            Workshop::updateOrCreate(
                [
                    'site_name' => 'connpass',
                    'event_id' => $workshop['event_id']
                ],
                [
                    'title' => $workshop['title'],
                    'catch' => $workshop['catch'],
                    'description' => $workshop['description'],
                    'event_url' => $workshop['event_url'],
                    'address' => $workshop['address'],
                    'place' => $workshop['place'],
                    'lat' => $workshop['lat'],
                    'lon' => $workshop['lon'],
                    'started_at' => Carbon::create(($workshop['started_at']))->format('Y-m-d H:i:s'),
                    'ended_at' => Carbon::create(($workshop['ended_at']))->format('Y-m-d H:i:s'),
                    'limit' => $workshop['limit'],
                    'participants' => $workshop['accepted'],
                    'waiting' => $workshop['waiting'],
                    'owner_id' => $workshop['owner_id'],
                    'owner_nickname' => $workshop['owner_nickname'],
                    'owner_display_name' => $workshop['owner_display_name'],
                    'group_id' => $workshop['series']['id'],
                    'event_updated_at' => Carbon::create(($workshop['updated_at']))->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
