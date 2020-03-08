<?php

namespace App\Domain\Event;

use App\Models\Prefecture;
use DateTime;

class Event
{
    private $site_name;
    private $event_id;
    private $title;
    private $catch;
    private $description;
    private $event_url;
    private $prefecture_id;
    private $address;
    private $place;
    private $lat;
    private $lon;
    private $started_at;
    private $ended_at;
    private $limit;
    private $participants;
    private $waiting;
    private $owner_id;
    private $owner_nickname;
    private $owner_twitter_id;
    private $owner_display_name;
    private $group_id;
    private $event_created_at;
    private $event_updated_at;

    public function __construct(
        ?int $id,
        string $site_name,
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
        ?string $owner_twitter_id,
        ?string $owner_display_name,
        ?string $group_id,
        ?DateTime $event_created_at,
        ?DateTime $event_updated_at
    )
    {
        $this->id = $id;
        $this->site_name = $site_name;
        $this->event_id = $event_id;
        $this->title = $title;
        $this->catch = $catch;
        $this->description = $description;
        $this->prefecture_id = $prefecture_id;
        $this->event_url = $event_url;
        $this->address = $address;
        $this->place = $place;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->started_at = $started_at;
        $this->ended_at = $ended_at;
        $this->limit = $limit;
        $this->participants = $participants;
        $this->waiting = $waiting;
        $this->owner_id = $owner_id;
        $this->owner_nickname = $owner_nickname;
        $this->owner_twitter_id = $owner_twitter_id;
        $this->owner_display_name = $owner_display_name;
        $this->group_id = $group_id;
        $this->event_created_at = $event_created_at;
        $this->event_updated_at = $event_updated_at;
    }


    //FIXME Prefectureをドメインに変更
    public function updatePrefectureId(?Prefecture $prefecture)
    {
        $this->prefecture_id = $prefecture ? $prefecture->id : null;
    }

    /**
     * Repository以外での呼び出し禁止
     * @param int $id
     */
    public function setId(int $id){
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->site_name;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getCatch(): ?string
    {
        return $this->catch;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getEventUrl(): ?string
    {
        return $this->event_url;
    }

    /**
     * @return mixed
     */
    public function getPrefectureId()
    {
        return $this->prefecture_id;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getPlace(): ?string
    {
        return $this->place;
    }

    /**
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @return float|null
     */
    public function getLon(): ?float
    {
        return $this->lon;
    }

    /**
     * @return DateTime|null
     */
    public function getStartedAt(): ?DateTime
    {
        return $this->started_at;
    }

    /**
     * @return DateTime|null
     */
    public function getEndedAt(): ?DateTime
    {
        return $this->ended_at;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function getParticipants(): ?int
    {
        return $this->participants;
    }

    /**
     * @return int|null
     */
    public function getWaiting(): ?int
    {
        return $this->waiting;
    }

    /**
     * @return int|null
     */
    public function getOwnerId(): ?int
    {
        return $this->owner_id;
    }

    /**
     * @return string|null
     */
    public function getOwnerNickname(): ?string
    {
        return $this->owner_nickname;
    }

    /**
     * @return string|null
     */
    public function getOwnerTwitterId(): ?string
    {
        return $this->owner_twitter_id;
    }

    /**
     * @return string|null
     */
    public function getOwnerDisplayName(): ?string
    {
        return $this->owner_display_name;
    }

    /**
     * @return string|null
     */
    public function getGroupId(): ?string
    {
        return $this->group_id;
    }

    /**
     * @return DateTime|null
     */
    public function getEventCreatedAt(): ?DateTime
    {
        return $this->event_created_at;
    }

    /**
     * @return DateTime|null
     */
    public function getEventUpdatedAt(): ?DateTime
    {
        return $this->event_updated_at;
    }


}
