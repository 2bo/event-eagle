<?php

namespace App\Console\Commands;

use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use App\Models\Event;

class TaggingForEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagging:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'tagging for events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $characterLimit = 200; //descriptionのタグの探索範囲
        $events = Event::all();
        $tags = Tag::all();

        foreach ($events as $event) {
            $tagIds = [];
            foreach ($tags as $tag) {
                $name = $tag->name;
                $description = mb_substr($event->description, 0, $characterLimit);
                if (mb_strpos($event->title, $name) !== false || mb_strpos($event->catch, $name) !== false
                    || mb_strpos($description, $name) !== false) {
                    $tagIds[] = $tag->id;
                }
            }
            $event->tags()->sync($tagIds);
        }
    }
}
