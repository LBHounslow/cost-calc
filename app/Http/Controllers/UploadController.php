<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use View;
use App\Http\Requests;
use Carbon\Carbon;
use App\Upload_log;
use App\Events\UploadFile;
use App\FileType;
use App\HousingTempAccom;
use App\AdultSocialCareServices;


class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allowedFileTypes = auth()->user()->getAllowedFileTypes();
        $uploadedFiles = Upload_log::whereIn('fileType', $allowedFileTypes)
            ->orderBy('id', 'desc')
            ->get();
        return View::make('upload/index', ['uploadedFiles' => $uploadedFiles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fileTypes = FileType::all();

        return View::make('upload/create', ['fileTypes' => $fileTypes]);
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
            'uploadFile' => 'mimes:xls,xlsx'
        ]);


        $now = Carbon::now();

        $dir = 'uploads/' . $now->format('Ym');
        $uploadname = str_replace(' ', '', $request->file('uploadFile')->getClientOriginalName());
        $filename = $now->timestamp . '_' . $uploadname;

        $path = $request->uploadFile->storeAs($dir, $filename);

        $uploadedFile = Upload_log::create([
            'original_filename' => $request->file('uploadFile')->getClientOriginalName(),
            'path' => $path,
            'filetype' => $request->input('fileType'),
            'user_id' => auth()->user()->id,
        ]);

        //event(new UploadFile($uploadedFile));

        flash('File successfully uploaded - it will be processed in the background - please refresh the page in about 5 mins');
        return Redirect::to('/uploads');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $uploadFileRecord = Upload_log::find($id);
        $allowedFileTypes = auth()->user()->getAllowedFileTypes();
        if (in_array($uploadFileRecord->filetype, $allowedFileTypes)) {
            if ($uploadFileRecord->filetype == 'asc01') {
                $deletedRows = AdultSocialCareServices::where('upload_id', $uploadFileRecord->id)->delete();
            } elseif ($uploadFileRecord->filetype == 'h01') {
                $deletedRows = HousingTempAccom::where('upload_id', $uploadFileRecord->id)->delete();
            } else {
                flash('No records were found - nothing was deleted');
                return Redirect::to('/uploads');
            }
        } else {
            flash('You do not have permission to delete this file');
            return Redirect::to('/uploads');
        }


        $uploadFileRecord->deleted = 1;
        $uploadFileRecord->error_msg = 'File deleted by user ' . auth()->user()->id . ' - ' . $deletedRows . ' records were deleted';
        $uploadFileRecord->save();

        flash($deletedRows . ' records were deleted');
        return Redirect::to('/uploads');

    }
}
