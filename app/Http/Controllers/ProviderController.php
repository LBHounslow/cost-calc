<?php

namespace App\Http\Controllers;

use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\FileType;
use View;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::all();
        return View::make('providers/index', ['providers' => $providers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fileTypes = FileType::all();
        return View::make('providers/create', ['fileTypes' => $fileTypes]);

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
            'displayName' => 'required|max:255',
            'allowedFileTypes' => 'required',
        ]);

        $fileType = new Provider;
        $fileType->create([
            'display_name' => $request->input('displayName'),
            'allowed_file_types' => json_encode($request->input('allowedFileTypes')),
        ]);

        flash('File Type successfully created');

        return Redirect::to('/providers');
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
        $fileTypes = FileType::all();

        $provider = Provider::find($id);
        return View::make('providers/edit', ['provider' => $provider, 'fileTypes' => $fileTypes]);
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
            'displayName' => 'required|max:255',
            'allowedFileTypes' => 'required',
        ]);

        $provider = Provider::find($id);

        $provider->display_name = $request->input('displayName');
        $provider->allowed_file_types = json_encode($request->input('allowedFileTypes'));
        $provider->save();

        flash('Provider successfully updated');
        return Redirect::to('/providers');
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
