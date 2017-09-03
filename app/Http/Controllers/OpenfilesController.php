<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Http\Request;

class OpenfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folder_path = "<a href=\"".route('openfiles.index')."\"><span class=\"glyphicon glyphicon-folder-open\"></span> 根目錄</a> / ";
        $folder_id = 0;
        $uploads = Upload::where('folder_id',$folder_id)->orderBy('name')->get();
        $data = compact("folder_id",'uploads','folder_path');
        return view('openfiles.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $att = $request->all();
        $att['fun']=1;//1是公開文件的功能，2是不公開
        $att['who_do'] = auth()->user()->job_title;

        if($request->input('type')==1){
            Upload::create($att);
        }else{
            //處理檔案上傳
            if ($request->hasFile('upload')) {
                $files = $request->file('upload');
                $folder = 'openfiles/'.date('Ymd');
                foreach($files as $file){
                    $info = [
                        'mime-type' => $file->getMimeType(),
                        'original_filename' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                        'size' => $file->getClientSize(),
                    ];
                    if ($info['size'] > 5100000)
                    {

                    } else {
                        $filename = $info['original_filename'];
                        $file->storeAs('public/' . $folder, $filename);
                        $att['name'] = date('Ymd') . '&' . $filename;
                        Upload::create($att);
                    }
                }
            }
        }

        return redirect()->route('openfiles.show',$att['folder_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $folder_id = $id;
        $uploads = Upload::where('folder_id',$folder_id)->orderBy('name')->get();



        $find_folder_id = $folder_id;
        $folder_path = "";
        while ($find_folder_id <> 0){
            $last_folder = Upload::where('id',$find_folder_id)->where('type',1)->first();
            $folder_path = "<a href=\"{$find_folder_id}\"><span class=\"glyphicon glyphicon-folder-open\"></span> ".$last_folder->name . "</a> / " .$folder_path;
            $find_folder_id = $last_folder->folder_id;
        }
        $folder_path = "<a href=\"".route('openfiles.index')."\"><span class=\"glyphicon glyphicon-folder-open\"></span> 根目錄</a> / " . $folder_path;

        $data = compact("folder_id",'uploads','folder_path');
        return view('openfiles.index',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function downloadfile($downloadfile)
    {
        if ($downloadfile) {
            $downloadfile = str_replace("&","/",$downloadfile);
            $filename = explode('/',$downloadfile);
            $realFile = "../storage/app/public/openfiles/".$downloadfile;
            header("Content-type:application");
            header("Content-Length: " .(string)(filesize($realFile)));
            header("Content-Disposition: attachment; filename=".$filename[1]);
            readfile($realFile);
        } else {
            return null;
        }
    }
}
