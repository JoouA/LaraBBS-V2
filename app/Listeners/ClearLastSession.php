<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache;
use Session;

class ClearLastSession
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     * 当登录的时候，根据Cache存在的session_id，清除用的session
     * @param  Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $last_session_id = 'lastSessionField' . $event->user->id;

        // 先清除上一个的session
        $lastSessionId = Cache::pull($last_session_id);

        Session::getHandler()->destroy($last_session_id);
    }
}
