<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchAtndEventsFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:atnd
    {--e|event_id=* : event id}
    {--k|keyword=* : keyword(and)}
    {--ko|keyword_or=* : keyword(or)}
    {--m|ym=* : year month}
    {--d|ymd=* : year month day}
    {--u|user_id=* : participant user id}
    {--un|nickname=* : participant nickname}
    {--ut|twitter_id=* : participant twitter id}
    {--o|owner_id=* : event owner id}    
    {--on|owner_nickname : event owner nickname}
    {--ot|owner_twitter_id : event owner twitter id}
    {--s|start=1 : the page number of search result}
    {--c|count=100 : the maximum number of output data}
    {--f|format=json : response format(xml,json,jsonp,atom,ics)} 
    {--a|all=1 : paging to get all records(0:false, 1:true) }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch events from ATND api and store to db';

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
        //
    }
}
