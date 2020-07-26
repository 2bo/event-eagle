<?php


namespace App\QueryServices;

use DateTime;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EventQueryService implements EventQueryServiceInterface
{
    public function searchEvent(?DateTime $from = null, ?DateTime $to = null, ?string $freeText = null,
                                ?array $prefectures = null, ?array $types = null, ?bool $isOnline = null,
                                int $page = 1, int $perPage = 15): PaginateResult
    {
        $queryBuilder = DB::table('events');
        //select
        $queryBuilder->select('events.id as id');
        $queryBuilder->addSelect('events.event_id as event_id');
        $queryBuilder->addSelect('events.site_name as site_name');
        $queryBuilder->addSelect('events.title as title');
        $queryBuilder->addSelect('events.catch as catch');
        $queryBuilder->addSelect('events.event_url as event_url');
        $queryBuilder->addSelect('events.address as address');
        $queryBuilder->addSelect('events.place as place');
        $queryBuilder->addSelect('events.lat as lat');
        $queryBuilder->addSelect('events.lon as lon');
        $queryBuilder->addSelect('events.started_at as started_at');
        $queryBuilder->addSelect('events.ended_at as ended_at');
        $queryBuilder->addSelect('events.limit as limit');
        $queryBuilder->addSelect('events.participants as participants');
        $queryBuilder->addSelect('events.waiting as waiting');
        $queryBuilder->addSelect('events.owner_nickname as owner_nickname');
        $queryBuilder->addSelect('events.owner_twitter_id as owner_twitter_id');
        $queryBuilder->addSelect('events.owner_display_name as owner_display_name');
        $queryBuilder->addSelect('events.event_created_at as created_at');
        $queryBuilder->addSelect('events.event_updated_at as updated_at');
        $queryBuilder->addSelect('events.is_online as is_online');
        $queryBuilder->addSelect('prefectures.name as prefecture_name');
        $queryBuilder->addSelect(DB::raw("group_concat(DISTINCT CONCAT(event_types.id, ':', event_types.name)) as types"));
        $queryBuilder->addSelect(DB::raw("group_concat(DISTINCT CONCAT(tags.id, ':', tags.name, ':', tags.url_name)) as tags"));
        //join
        $queryBuilder = $this->addJoinTables($queryBuilder);
        //where
        $queryBuilder = $this->addWhereConditions($queryBuilder, $from, $to, $freeText, $prefectures, $types, $isOnline);
        //group by
        $queryBuilder->groupBy('events.id');
        //order by
        $queryBuilder->orderBy('events.started_at');
        //offset limit
        $queryBuilder->offset(($page - 1) * $perPage);
        $queryBuilder->limit($perPage);
        //データ整形
        $data = $queryBuilder->get()->map(function ($item) {
            //タイプの整形
            if ($item->types) {
                //id:name, id:name, ...を1つのタイプ分に分割
                $types = explode(',', $item->types);
                $item->types = [];
                foreach ($types as $type) {
                    //id:nameの項目ごとに分割
                    $typeArray = explode(':', $type);
                    $item->types[] = ['id' => $typeArray[0], 'name' => $typeArray[1]];
                }
            }
            //タグの整形
            if ($item->tags) {
                //id:name:url_name, id:name:url_name, ...1つのタグ分に分割
                $tags = explode(',', $item->tags);
                $item->tags = [];
                foreach ($tags as $tag) {
                    //id:name:url_nameの項目ごとに分割
                    $tagArray = explode(':', $tag);
                    $item->tags[] = ['id' => $tagArray[0], 'name' => $tagArray[1], 'url_name' => $tagArray[2]];
                }
            }
            return $item;
        })->toArray();
        $total = $this->getSearchEventTotalCount($from, $to, $freeText, $prefectures, $types, $isOnline);
        return new PaginateResult($total, $perPage, $page, $data);
    }

    private function getSearchEventTotalCount(?DateTime $from = null, ?DateTime $to = null, ?string $freeText = null, ?array $prefectures = null,
                                              ?array $types = null, ?bool $isOnline = null): int
    {
        $queryBuilder = DB::table('events');
        //select
        $queryBuilder->Select('events.id');
        // join
        $queryBuilder = $this->addJoinTables($queryBuilder);
        //where
        $queryBuilder = $this->addWhereConditions($queryBuilder, $from, $to, $freeText, $prefectures, $types, $isOnline);
        return $queryBuilder->distinct()->count('events.id');
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

    private function addWhereConditions(Builder $builder, ?DateTime $from = null, ?DateTime $to = null, ?string $freeText = null,
                                        ?array $prefectures = null, ?array $types = null, ?bool $isOnline = null): Builder
    {
        if (!empty($from)) {
            $builder->whereDate('started_at', '>=', $from);
        }
        if (!empty($to)) {
            $builder->whereDate('started_at', '<=', $to);
        }
        if (!empty($freeText)) {
            $builder->whereRaw('MATCH (title, catch, description, place, address) AGAINST (? IN BOOLEAN MODE)',
                array($freeText));
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

        return $builder;
    }

}
