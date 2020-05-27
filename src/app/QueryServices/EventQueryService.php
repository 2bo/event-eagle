<?php


namespace App\QueryServices;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EventQueryService implements EventQueryServiceInterface
{
    public function searchEvent(?string $freeText = null, ?array $prefectures = null, ?array $types = null, ?bool $isOnline = null,
                                int $page = 1, int $perPage = 15): PaginateResult
    {
        $events = DB::table('events');
        //select
        $events->select('events.id');
        $events->addSelect('events.event_id');
        $events->addSelect('events.site_name');
        $events->addSelect('events.title');
        $events->addSelect('events.catch');
        $events->addSelect('events.description');
        $events->addSelect('events.event_url');
        $events->addSelect('events.address');
        $events->addSelect('events.place');
        $events->addSelect('events.lat');
        $events->addSelect('events.lon');
        $events->addSelect('events.started_at');
        $events->addSelect('events.ended_at');
        $events->addSelect('events.limit');
        $events->addSelect('events.participants');
        $events->addSelect('events.waiting');
        $events->addSelect('events.owner_nickname');
        $events->addSelect('events.owner_twitter_id');
        $events->addSelect('events.owner_display_name');
        $events->addSelect('events.event_created_at');
        $events->addSelect('events.event_updated_at');
        $events->addSelect('events.is_online');
        $events->addSelect('prefectures.name');
        $events->addSelect(DB::raw("group_concat(DISTINCT event_types.name) as event_types"));
        $events->addSelect(DB::raw("group_concat(DISTINCT tags.name) as tags"));
        //join
        $events = $this->addJoinTables($events);
        //where
        $events = $this->addWhereConditions($events, $freeText, $prefectures, $types, $isOnline);
        //group by
        $events->groupBy('events.id');
        //order by
        $events->orderBy('events.started_at');
        //offset limit
        $events->offset(($page - 1) * $perPage);
        $events->limit($perPage);

        $data = $events->get()->toArray();
        $total = $this->getSearchEventTotalCount($freeText, $prefectures, $types, $isOnline);

        return new PaginateResult($total, $perPage, $page, $data);
    }

    private function getSearchEventTotalCount(?string $freeText = null, ?array $prefectures = null, ?array $types = null, ?bool $isOnline = null)
    {
        $events = DB::table('events');
        //select
        $events->Select('events.id');
        // join
        $events = $this->addJoinTables($events);
        //where
        $events = $this->addWhereConditions($events, $freeText, $prefectures, $types, $isOnline);
        return $events->distinct()->count('events.id');
    }

    private function addJoinTables(Builder $builder): Builder
    {
        $builder->leftJoin('prefectures', 'events.prefecture_id', '=', 'prefectures.id');
        $builder->leftJoin('event_event_type as eet', 'eet.event_id', '=', 'events.id');
        $builder->leftJoin('event_types', 'event_types.id', '=', 'eet.event_type_id');
        $builder->leftJoin('event_tag', 'events.id', '=', 'event_tag.event_id');
        $builder->leftJoin('tags', 'tags.id', '=', 'event_tag.tag_id');
        return $builder;
    }

    private function addWhereConditions(Builder $builder, ?string $freeText, ?array $prefectures = null, ?array $types = null, ?bool $isOnline = null): Builder
    {
        if (!empty($freeText)) {
            $builder->whereRaw('MATCH (title, catch, description, place, address) AGAINST (? IN BOOLEAN MODE)', array($freeText));
        }
        if (!empty($types)) {
            $builder->whereExists(function ($query) use ($types) {
                $query->select(DB::raw(1));
                $query->from('events as sub_events');
                $query->leftJoin('event_event_type as sub_eet', 'sub_eet.event_id', '=', 'sub_events.id');
                $query->leftJoin('event_types as sub_event_types', 'sub_event_types.id', '=', 'sub_eet.event_type_id');
                $query->whereIn('sub_event_types.id', $types);
                $query->whereRaw('events.id = sub_events.id');
                return $query;
            });
        }
        if (!empty($prefectures) && !empty($isOnline)) {
            $builder->where(function ($query) use ($prefectures, $isOnline) {
                return $query->whereIn('prefectures.id', $prefectures)
                    ->orWhere('events.is_online', (int)$isOnline);
            });
        } else {
            if (!empty($prefectures)) {
                $builder->whereIn('prefectures.id', $prefectures);
            }

            if (!is_null($isOnline)) {
                $builder->Where('events.is_online', (int)$isOnline);
            }
        }

        $now = (new \DateTime())->format('Y-m-d 0:0:0');
        $builder->where('events.started_at', '>=', $now);
        return $builder;
    }

}
