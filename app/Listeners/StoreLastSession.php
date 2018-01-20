<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Session;
use Cache;

class StoreLastSession
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * 当用户登陆成功的时候，将用户的session_id存入cache当中，
     * @param  Authenticated $event
     * @return void
     */
    public function handle(Authenticated $event)
    {
        $current_session_id = Session::getId();

        $current_session_cache_key = 'lastSessionField' . $event->user->id;

        Cache::forever($current_session_cache_key, $current_session_id);
    }
}
