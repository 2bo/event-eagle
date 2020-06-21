<?php

namespace App\Domain\Models\Event;

use App\Domain\Models\Prefecture\PrefectureId;
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
    private $is_online;
    private $types;
    private $tags;

    public function __construct(
        ?int $id,
        string $site_name,
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
        ?string $owner_twitter_id = null,
        ?string $owner_display_name = null,
        ?int $group_id = null,
        ?DateTime $event_created_at = null,
        ?DateTime $event_updated_at = null,
        bool $is_online = false,
        array $types = [],
        array $tags = []
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
        $this->is_online = $is_online;
        $this->setEventTypes($types);
        $this->setTags($tags);
    }


    public function updatePrefectureId(?PrefectureId $prefectureId)
    {
        $this->prefecture_id = $prefectureId;
    }

    public function setEventTypes(array $eventTypes = [])
    {
        if (!$eventTypes) {
            $this->types = [];
            return;
        }
        foreach ($eventTypes as $eventType) {
            if (is_a($eventType, EventType::class)) {
                $this->types[] = $eventType;
            }
        }
    }

    public function setTags(array $tags = [])
    {
        if (!$tags) {
            $this->tags = [];
        }
        foreach ($tags as $tag) {
            if (is_a($tag, Tag::class)) {
                $this->tags[] = $tag;
            }
        }
    }

    public function updateIsOnline()
    {
        $onlineKeywords = [
            'online',
            'オンライン',
            'remote',
            'リモート',
            'Zoom',
            'Skype',
            'Discord',
            'Hangout'
        ];

        $targets = ['title', 'catch', 'description'];

        foreach ($onlineKeywords as $keyword) {
            foreach ($targets as $target) {
                if (mb_stripos($this->$target, $keyword) !== false) {
                    $this->is_online = true;
                    return;
                }
            }
        }
    }


    /**
     * @return int
     */
    public function getId(): int
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

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->is_online;
    }

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

}
