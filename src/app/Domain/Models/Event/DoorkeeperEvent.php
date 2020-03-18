<?php


namespace App\Domain\Models\Event;

use App\Domain\Models\Prefecture\PrefectureId;
use DateTime;

class DoorkeeperEvent extends Event
{
    const SITE_NAME_DOORKEEPER = 'doorkeeper.jp';

    public function __construct(
        ?int $id,
        int $event_id,
        ?string $title = null,
        ?DateTime $starts_at = null,
        ?DateTime $ends_at = null,
        ?string $venue_name = null,
        ?string $address = null,
        ?float $lat = null,
        ?float $long = null,
        ?int $ticket_limit = null,
        ?DateTime $published_at = null,
        ?DateTime $updated_at = null,
        ?int $group = null,
        ?string $description = null,
        ?string $public_url = null,
        ?int $participants = null,
        ?int $waitlisted = null,
        ?PrefectureId $prefecture_id = null
    )
    {
        parent::__construct(
            $id,
            self::SITE_NAME_DOORKEEPER,
            $event_id,
            $title,
            null,
            $description,
            $public_url,
            $prefecture_id,
            $address,
            $venue_name,
            $lat,
            $long,
            $starts_at,
            $ends_at,
            $ticket_limit,
            $participants,
            $waitlisted,
            null,
            null,
            null,
           null,
            $group,
            $published_at,
            $updated_at
        );
    }
}
