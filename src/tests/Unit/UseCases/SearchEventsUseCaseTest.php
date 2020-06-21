<?php

namespace Tests\Unit\UseCases;

use App\QueryServices\EventQueryService;
use App\QueryServices\EventQueryServiceInterface;
use App\QueryServices\PaginateResult;
use App\UseCases\SearchEvents\SearchEventsInputData;
use App\UseCases\SearchEvents\SearchEventsOutputData;
use App\UseCases\SearchEvents\SearchEventsUseCase;
use DateTime;
use Tests\TestCase;

class SearchEventsUseCaseTest extends TestCase
{

    public function testHandle()
    {
        $from = new DateTime('2020-01-01');
        $to = new DateTime('2020-03-31');
        $keyword = 'keyword';
        $prefectures = [1, 2];
        $types = [3, 4];
        $isOnline = true;
        $page = 5;

        $queryServiceMock = \Mockery::mock(EventQueryService::class);
        $queryServiceMock->shouldReceive('searchEvent')
            ->with(
                \Mockery::on(function ($actual) use ($from) {
                    return $actual->format('Ymd') === $from->format('Ymd');
                }),
                \Mockery::on(function ($actual) use ($to) {
                    return $actual->format('Ymd') === $to->format('Ymd');
                }),
                $keyword, $prefectures, $types, $isOnline, $page
            )
            ->once()
            ->andReturn(new PaginateResult(10, 5, 1, []));
        //Mockに差し替える
        $this->app->singleton(EventQueryServiceInterface::class, function () use ($queryServiceMock) {
            return $queryServiceMock;
        });
        //コンテナを使ってインスタンスを取得
        $useCase = app(SearchEventsUseCase::class);
        $input = new SearchEventsInputData($from, $to, $keyword, $prefectures, $types, $isOnline, $page);
        $output = $useCase->handle($input);
        self::assertInstanceOf(SearchEventsOutputData::class, $output);
        self::assertInstanceOf(PaginateResult::class, $output->getPaginateResult());
    }

    //イベントの期間の条件を指定しない場合、UseCaseでデフォルトの期間を指定する挙動のテスト
    public function testHandleWithOutFromAndToConditions()
    {
        //QueryServiceのモック
        $queryServiceMock = \Mockery::mock(EventQueryService::class);
        $queryServiceMock->shouldReceive('searchEvent')
            ->with(
                \Mockery::on(function ($actual) {
                    //from条件がない場合、UseCaseで今日を指定する
                    $today = (new DateTime())->format('Ymd');
                    return $actual->format('Ymd') === $today;
                }),
                \Mockery::on(function ($actual) {
                    //from条件がない場合、UseCaseで3ヶ月後を指定する
                    $after3Month = (new DateTime('+ 3 month'))->format('Ymd');
                    return $actual->format('Ymd') === $after3Month;
                }),
                null, null, null, null, 1
            )
            ->once()
            ->andReturn(new PaginateResult(10, 5, 1, []));
        //Mockに差し替える
        $this->app->singleton(EventQueryServiceInterface::class, function () use ($queryServiceMock) {
            return $queryServiceMock;
        });
        //コンテナを使ってインスタンスを取得
        $useCase = app(SearchEventsUseCase::class);
        //page以外すべて条件がない
        $input = new SearchEventsInputData(null, null, null, null, null, null, 1);
        $output = $useCase->handle($input);
        self::assertInstanceOf(SearchEventsOutputData::class, $output);
        self::assertInstanceOf(PaginateResult::class, $output->getPaginateResult());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }
}
