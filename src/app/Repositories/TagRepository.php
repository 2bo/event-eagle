<?php


namespace App\Repositories;


use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TagRepository
{
    public function getModelClass(): string
    {
        return Tag::class;
    }


    public function saveTagsFromNames(array $tagNames)
    {
        foreach ($tagNames as $tagName) {
            Tag::updateOrCreate(['name' => $tagName], ['pattern' => '/' . $tagName . '/u']);
        }
    }
}
