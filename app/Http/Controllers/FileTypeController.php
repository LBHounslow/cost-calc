<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\FileType;
use App\ImportModel;
use App\ImportScript;
use View;

class FileTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fileTypes = FileType::all();
        return View::make('filetypes/index', ['fileTypes' => $fileTypes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $importScripts = ImportScript::all();
        $importModels = ImportModel::all();

        return View::make('filetypes/create', ['importScripts' => $importScripts, 'importModels' => $importModels]);
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
            'code' => 'required|max:255|unique:file_types',
            'scriptPath' => 'required',
            'modelPath' => 'required',
        ]);

        $fileType = new FileType;
        $fileType->create([
            'display_name' => $request->input('displayName'),
            'code' => $request->input('code'),
            'import_script_id' => $request->input('scriptPath'),
            'import_model_id' => $request->input('modelPath'),
        ]);

        flash('File Type successfully created');

        return Redirect::to('/filetypes');
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
        $importScripts = ImportScript::all();
        $importModels = ImportModel::all();

        $fileType = FileType::find($id);
        return View::make('filetypes/edit', ['fileType' => $fileType, 'importScripts' => $importScripts, 'importModels' => $importModels]);
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
            'code' => 'required|max:255|unique:file_types,code,' . $id,
            'scriptPath' => 'required',
            'modelPath' => 'required',
        ]);

        $fileType = FileType::find($id);

        $fileType->display_name = $request->input('displayName');
        $fileType->code = $request->input('code');
        $fileType->import_script_id = $request->input('scriptPath');
        $fileType->import_model_id = $request->input('modelPath');
        $fileType->save();

        flash('File Type successfully updated');
        return Redirect::to('/filetypes');
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
