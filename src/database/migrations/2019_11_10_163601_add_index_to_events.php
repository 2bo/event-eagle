<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('title')->change();
            $table->index('title');
            $table->index('event_id');
            $table->index('started_at');
            $table->index('ended_at');
            $table->index(['group_id', 'site_name']);
            $table->dropUnique('events_site_name_event_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_title_index');
            $table->dropIndex('events_event_id_index');
            $table->dropIndex('events_started_at_index');
            $table->dropIndex('events_ended_at_index');
            $table->dropIndex('events_group_id_site_name_index');
            $table->unique(['site_name', 'event_id']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->text('title')->change();
        });

    }
}
