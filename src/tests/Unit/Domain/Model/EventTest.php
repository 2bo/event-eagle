<?php

namespace Tests\Unit\Domain\Model;

use App\Domain\Models\Event\Tag;
use App\Domain\Models\Event\Event;
use Tests\TestCase;

class EventTest extends TestCase
{

    public function testUpdateIsOnline()
    {
        //タイトルにキーワードを含む場合true
        $event = new Event(null, 'site_name', 1, '【オンライン】');
        $event->updateIsOnline();
        self::assertEquals(true, $event->isOnline());

        //キャッチにキーワードを含む場合true
        $event = new Event(null, 'site_name', 1, null, '【オンライン】');
        $event->updateIsOnline();
        self::assertEquals(true, $event->isOnline());

        //descriptionにキーワードを含む場合true
        $event = new Event(null, 'site_name', 1, null, null, '【オンライン】');
        $event->updateIsOnline();
        self::assertEquals(true, $event->isOnline());

        //オンライン開催のキーワードが存在しないときはfalse
        $event = new Event(null, 'site_name', 1, '【オフライン】');
        $event->updateIsOnline();
        self::assertEquals(false, $event->isOnline());

        //大文字小文字を区別しない
        $event = new Event(null, 'site_name', 1, 'Zoom');
        $event->updateIsOnline();
        self::assertEquals(true, $event->isOnline());

        $event = new Event(null, 'site_name', 1, 'zoom');
        $event->updateIsOnline();
        self::assertEquals(true, $event->isOnline());
    }

    public function testSetTags()
    {
        $event = new Event(null, '', '1');
        // Tagクラスの配列以外はセットされない
        $event->setTags([1, 2]);
        self::assertEmpty($event->getTags());
        // Tagクラスの配列はセットされる
        $tags = [new Tag(1, 'PHP'), new Tag(2, 'Laravel')];
        $event->setTags($tags);
        self::assertEquals($tags, $event->getTags());
    }
}
