<?php


namespace App\Domain\Models\Event;

use App\Domain\Models\Prefecture\PrefectureId;
use DateTime;

class ConnpassEvent extends Event
{
    const SITE_NAME_CONNPASS = 'connpass.com';

    public function __construct(
        ?int $id,
        int $event_id,
        ?string $title = null,
        ?string $catch = null,
        ?string $description = null,
        ?string $event_url = null,
        ?PrefectureId $prefecture_id = null,
        ?string $address = null,
        ?string $place = null,
        ?float $lat = null,
        ?float $lon = null,
        ?DateTime $started_at = null,
        ?DateTime $ended_at = null,
        ?int $limit = null,
        ?int $participants = null,
        ?int $waiting = null,
        ?int $owner_id = null,
        ?string $owner_nickname = null,
        ?string $owner_display_name = null,
        ?string $group_id = null,
        ?DateTime $event_updated_at = null
    )
    {
        parent::__construct(
            $id,
            self::SITE_NAME_CONNPASS,
            $event_id,
            $title,
            $catch,
            $description,
            $event_url,
            $prefecture_id,
            $address,
            $place,
            $lat,
            $lon,
            $started_at,
            $ended_at,
            $limit,
            $participants,
            $waiting,
            $owner_id,
            $owner_nickname,
            null,
            $owner_display_name,
            $group_id,
            null,
            $event_updated_at
        );
    }

}
