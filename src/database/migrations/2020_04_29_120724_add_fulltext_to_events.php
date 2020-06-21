<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltextToEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE events ADD FULLTEXT INDEX events_fulltext_index
                         (`title`,`catch`,`description`,`address`,`place`) COMMENT \'parser "TokenMecab"\'');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE events DROP INDEX events_fulltext_index');
    }
}
