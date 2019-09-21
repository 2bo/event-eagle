<?php

namespace App\Console\Commands;

use App\Models\Tag;
use App\Models\Workshop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class taggingForWorkshops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagging:workshops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'tagging for workshops';

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
        $characterLimit = 400; //descriptionのタグの探索範囲
        $workshops = Workshop::all();
        $tags = Tag::all();

        foreach ($workshops as $workshop) {
            $tagIds = [];
            foreach ($tags as $tag) {
                $name = $tag->name;
                $description = mb_substr($workshop->description, 0, $characterLimit);
                if (mb_strpos($workshop->title, $name) !== false || mb_strpos($workshop->catch, $name) !== false
                    || mb_strpos($description, $name) !== false) {
                    $tagIds[] = $tag->id;
                }
            }
            $workshop->tags()->sync($tagIds);
        }
    }
}
