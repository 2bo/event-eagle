<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DataModels\Event;
use Illuminate\Support\Facades\DB;

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
        $tags = DB::table('tags')->orderBy('id')->limit(400)->get();

        foreach ($events as $event) {
            $tagIds = [];
            foreach ($tags as $tag) {
                $pattern = '/' . $tag->pattern . '/';
                $description = mb_substr(strip_tags($event->description), 0, $characterLimit);
                if (preg_match($pattern, $event->title) || preg_match($pattern, $event->catch)
                    || preg_match($pattern, $description)) {
                    $tagIds[] = $tag->id;
                }
            }
            $event->tags()->sync($tagIds);
        }
    }
}
