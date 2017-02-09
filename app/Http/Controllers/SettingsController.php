<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User_login_log;
use View;
use App\Http\Requests;

class SettingsController extends Controller
{
    public function index()
    {
        return View::make('settings/index');
    }

    public function getUserLoginLogs()
    {
        $userLoginRequests = User_login_log::all();

        return View::make('settings/userLogin', ['userLoginRequests' => $userLoginRequests]);
    }
}
