<?php

namespace App\Listeners;

use App\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuspendUserFromLockout
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
     * @param  Lockout $event
     * @return void
     */
    public function handle(Lockout $event)
    {
        $user = User::where('email', $event->request->email)->first();

        $user->active = 0;
        $user->save();
    }
}
