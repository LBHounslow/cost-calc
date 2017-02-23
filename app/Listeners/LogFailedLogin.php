<?php

namespace App\Listeners;

use App\User_login_log;
use Carbon\Carbon;
use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogFailedLogin
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
     *
     * @param  Failed $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $login = new User_login_log;
        $login->create([
            'user_id' => $event->user->id,
            'login_user_name' => $event->user->name,
            'login_user_email' => $event->user->email,
            'login_client_ip' => request()->ip(),
            'success' => 0,
        ]);

    }
}
