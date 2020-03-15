<?php

use Illuminate\Database\Seeder;
use App\DataModels\Tag;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sfo = new SplFileObject('database/csv/tags.csv');
        $sfo->setFlags(
            SplFileObject::DROP_NEW_LINE |
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::READ_CSV
        );

        foreach ($sfo as $line) {
            Tag::updateOrCreate(['name' => $line[0]]);
        }

    }
}
