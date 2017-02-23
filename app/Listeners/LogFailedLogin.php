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


        slack();
        $login = new User_login_log;
        $login->create([
            'user_id' => 0,
            'login_user_name' => 0,
            'login_user_email' => $event->credentials['email'],
            'login_client_ip' => request()->ip(),
            'success' => 0,
        ]);

    }
}
