<?php


namespace App\Domain\Event;

use DateTime;

class ConnpassEvent extends Event
{
    const SITE_NAME = 'connpass.com';

    public function __construct(
        ?int $id,
        int $event_id,
        ?string $title,
        ?string $catch,
        ?string $description,
        ?string $event_url,
        ?int $prefecture_id,
        ?string $address,
        ?string $place,
        ?float $lat,
        ?float $lon,
        ?DateTime $started_at,
        ?DateTime $ended_at,
        ?int $limit,
        ?int $participants,
        ?int $waiting,
        ?int $owner_id,
        ?string $owner_nickname,
        ?string $owner_display_name,
        ?string $group_id,
        ?DateTime $event_updated_at
    )
    {
        parent::__construct(
            $id,
            self::SITE_NAME,
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
