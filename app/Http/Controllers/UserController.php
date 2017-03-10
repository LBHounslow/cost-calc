<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use App\User;
use App\User_change_log;
use View;
use App\Provider;
use App\Permission;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get a list of all users
        $users = User::all();

        // build view
        return View::make('user/index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $providers = Provider::all();
        $permissions = Permission::all();

        return View::make('user/create', ['providers' => $providers, 'permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'provider' => 'required',
            'permissions' => 'required',
        ]);

        $user = new User;
        $user->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'permissions' => json_encode($request->input('permissions')),
            'provider_id' => $request->input('provider'),
            'active' => 1,
        ]);

        flash('User successfully created');

        return Redirect::to('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $providers = Provider::all();
        $permissions = Permission::all();

        $user = User::find($id);
        return View::make('user/edit', ['user' => $user, 'providers' => $providers, 'permissions' => $permissions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'provider' => 'required',
            'permissions' => 'required',
        ]);

        $user = User::find($id);
        $oldUser = [
            'name' => $user->name,
            'email' => $user->email,
            'provider_id' => $user->provider_id,
            'permissions' => $user->permissions,
            'active' => $user->active,

        ];
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->provider_id = $request->input('provider');
        $user->permissions = json_encode($request->input('permissions'));

        if ($request->input('active')) {
            $user->active = 1;
        } else {
            $user->active = 0;
        }

        $user->save();

        $newUser = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'provider_id' => $request->input('provider'),
            'permissions' => json_encode($request->input('permissions')),
            'active' => $user->active,

        ];

        $userChange = new User_change_log();
        $userChange->create([
            'from' => json_encode($oldUser),
            'to' => json_encode($newUser),
            'user_id' => $user->id,
            'change_user_id' => \Auth::user()->id,
        ]);


        flash('User successfully updated');

        return Redirect::to('/users');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
