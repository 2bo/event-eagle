<?php

namespace Tests\Unit\Repositories;

use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Models\Event\Tag;

class TagRepositoryTest extends TestCase
{
    use RefreshDatabase;
    static private $repository;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$repository = new TagRepository();

    }

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function testFindByName()
    {
        $tag = self::$repository->findByName('PHP');
        self::assertInstanceOf(Tag::class, $tag);
        self::assertEquals('PHP', $tag->getName());

        //存在しない名前はnullを返す
        $tag = self::$repository->findByName('Nothing Tag');
        self::assertNull($tag);
    }
}
