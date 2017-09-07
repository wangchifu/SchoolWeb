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
        $folder_path = "<a href=\"".route('openfiles.index')."\" class='btn btn-warning btn-xs'><span class=\"glyphicon glyphicon-folder-open\"></span> 根目錄</a> / ";
        $folder_id = 0;
        $uploads = Upload::where('folder_id',$folder_id)->where('fun',1)->orderBy('type')->orderBy('name')->get();
        if(auth()->check()) $who_do = auth()->user()->job_title;

        $data = compact("folder_id",'uploads','folder_path','who_do');
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
                        //'mime-type' => $file->getMimeType(),
                        'original_filename' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                        'size' => $file->getClientSize(),
                    ];
                    if ($info['size'] > 10100000)
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
        //遇到 $id =0 跳到 index
        if($id==0) return redirect()->route('openfiles.index');

        //開啟誰的folder
        $open_folder = Upload::where('id',$id)->first();
        $who_do = $open_folder->who_do;

        $folder_id = $id;
        $uploads = Upload::where('folder_id',$folder_id)->where('fun',1)->orderBy('type')->orderBy('name')->get();




        $find_folder_id = $folder_id;
        $folder_path = "";
        while ($find_folder_id <> 0){
            $last_folder = Upload::where('id',$find_folder_id)->where('type',1)->first();
            $folder_path = "<a href=\"{$find_folder_id}\" class='btn btn-warning btn-xs'><span class=\"glyphicon glyphicon-folder-open\"></span> ".$last_folder->name . "</a> / " .$folder_path;
            $find_folder_id = $last_folder->folder_id;
        }
        $folder_path = "<a href=\"".route('openfiles.index')."\" class='btn btn-warning btn-xs'><span class=\"glyphicon glyphicon-folder-open\"></span> 根目錄</a> / " . $folder_path;

        $data = compact("folder_id",'uploads','folder_path','who_do');
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
    public function destroy(Upload $upload)
    {
        $folder_id = $upload->folder_id;
        if (auth()->user()->job_title == $upload->who_do) {
            if ($upload->type == 2) {
                $filename = str_replace("&", "/", $upload->name);
                $realFile = "../storage/app/public/openfiles/" . $filename;
                unlink($realFile);
            }else{
                $something = Upload::where('folder_id',$upload->id)->first();
                if($something != null){
                    return redirect()->route('openfiles.show',$folder_id);
                }
            }

            $upload->delete();
        }
        return redirect()->route('openfiles.show',$folder_id);
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
