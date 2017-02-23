<?php

namespace App\Listeners;

use App\User;
use App\User_change_log;
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

        $oldUser = [
            'name' => $user->name,
            'email' => $user->email,
            'provider_code' => $user->provider_code,
            'permissions' => $user->permissions,
            'active' => $user->active,
        ];

        $user->active = 0;
        $user->save();

        $newUser = [
            'name' => $user->name,
            'email' => $user->email,
            'provider_code' => $user->provider_code,
            'permissions' => $user->permissions,
            'active' => $user->active,
        ];

        $userChange = new User_change_log();
        $userChange->create([
            'from' => json_encode($oldUser),
            'to' => json_encode($newUser),
            'user_id' => $user->id,
            'change_user_id' => 0,
        ]);
    }
}
