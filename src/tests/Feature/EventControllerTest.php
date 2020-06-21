<?php

namespace Tests\Feature;

use App\QueryServices\PaginateResult;
use App\UseCases\SearchEvents\SearchEventsInputData;
use App\UseCases\SearchEvents\SearchEventsOutputData;
use App\UseCases\SearchEvents\SearchEventsUseCase;
use App\UseCases\SearchEvents\SearchEventsUseCaseInterface;
use Tests\TestCase;

class EventControllerTest extends TestCase
{

    public function testSearch()
    {
        $response = $this->get('api/events/search');
        $response->assertOk()
            ->assertJsonStructure(
                [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                    'data' => [
                        '*' => [
                            'id',
                            'event_id',
                            'site_name',
                            'title',
                            'catch',
                            'event_url',
                            'address',
                            'place',
                            'lat',
                            'lon',
                            'started_at',
                            'ended_at',
                            'limit',
                            'participants',
                            'waiting',
                            'owner_nickname',
                            'owner_twitter_id',
                            'owner_display_name',
                            'created_at',
                            'updated_at',
                            'is_online',
                            'prefecture_name',
                            'types',
                            'tags',
                        ]
                    ]
                ]
            );
    }

    //リクエストパラメータから変換してInputDataをUseCaseに正しく渡せていることのテスト
    public function testSearchRequestToInputData()
    {
        $keyword = 'keyword';
        $places = [1, 2];
        $types = [3, 4];
        $isOnline = true;
        $page = 2;
        //コントローラから渡されると予想する入力
        $expectedInput = new SearchEventsInputData(null, null, $keyword, $places, $types, $isOnline, $page);
        $paginateResult = new PaginateResult(30, 15, 2, []);
        $output = new SearchEventsOutputData($paginateResult);

        //ユースケースのモック
        $useCaseMock = \Mockery::mock(SearchEventsUseCase::class);
        $useCaseMock->shouldReceive('handle')
            ->with(\Mockery::on(function ($actualInput) use ($expectedInput) {
                $isSameFrom = is_null($actualInput->getFrom());
                $isSameTo = is_null($actualInput->getTo());
                $isSamePrefectures = $actualInput->getPrefectures() === $expectedInput->getPrefectures();
                $isSameTypes = $actualInput->getTypes() === $expectedInput->getTypes();
                $isSameOnline = $actualInput->isOnline() === $expectedInput->isOnline(); //placesに0が含まれる場合isOnline=true
                $isSamePage = $actualInput->getPage() === $expectedInput->getPage();
                return
                    $isSameFrom && $isSameTo && $isSamePrefectures && $isSameTypes && $isSameOnline && $isSamePage;
            }))
            ->once()
            ->andReturn($output);
        //モックの差し替え
        $this->app->bind(SearchEventsUseCaseInterface::class, function () use ($useCaseMock) {
            return $useCaseMock;
        });

        $online = 0;
        $response = $this->call('GET', 'api/events/search',
            [
                "keywords" => $keyword,
                "places" => array_merge($places, [$online]), //オンラインを追加
                "types" => $types,
                "page" => $page
            ]);

        $response->assertOk();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }
}

