<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Mfile;
use App\Morning;
use App\Report;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Morning $morning)
    {
        //$this->authorize('create', $morning);
        //如果已經發表過報告的，應該是變成再修改已經發表過的
        $report = Report::where('user_id',auth()->user()->id)->where('morning_id',$morning->id)->first();

        if($report) {
            $data = [
                'report_id'=>$report->id,
                'morning_id'=>$morning->id,
            ];
            return redirect()->route('reports.edit',$data);
        }

        $data = compact('morning');
        return view('reports.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        $attributes = $request->all();
        $attributes['user_id'] = auth()->user()->id;
        $morning_id = $request->input('morning_id');
        $attributes['morning_id'] = $morning_id;
        $attributes['who_do'] = auth()->user()->job_title." ".auth()->user()->name;
        $attributes['order_by'] = auth()->user()->order_by;
        $report = Report::create($attributes);

        //處理檔案上傳
        $att['report_id'] = $report->id;
        if ($request->hasFile('upload')) {
            //$attributes = $request->all();

            $files = $request->file('upload');

            $folder = 'reports/'.date('Ymd');
            foreach($files as $file) {
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];
                if ($info['size'] > 5100000) {


                } else {


                    $filename = $info['original_filename'];
                    $file->storeAs('public/' . $folder, $filename);

                    $att['name'] = date('Ymd') . '&' . $filename;

                    Mfile::create($att);
                }
            }
        }

        return redirect()->route('mornings.show',$morning_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Morning $morning,$report_id)
    {

        $report = Report::find($report_id);

        if($report->user_id <> auth()->user()->id) return redirect()->route('mornings.show',$report->morning_id);

        $data = [
            'report'=>$report,
            'morning'=>$morning,
            'mfiles'=>$report->mfiles,
        ];
        return view('reports.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReportRequest $request,Report $report)
    {
        $report->update($request->all());

        //處理檔案上傳
        $att['report_id'] = $report->id;
        if ($request->hasFile('upload')) {
            //$attributes = $request->all();

            $files = $request->file('upload');

            $folder = 'reports/'.date('Ymd');
            foreach($files as $file) {
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];
                if ($info['size'] > 5100000) {


                } else {


                    $filename = $info['original_filename'];
                    $file->storeAs('public/' . $folder, $filename);

                    $att['name'] = date('Ymd') . '&' . $filename;

                    Mfile::create($att);
                }
            }
        }

        return redirect()->route('mornings.show',$report->morning_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        $morning_id = $report->morning_id;

        //先刪附檔
        $mfiles = $report->mfiles;
        foreach($mfiles as $mfile){
            $mfile->delete();

            $filename = str_replace("&","/",$mfile->name);

            $realFile = "../storage/app/public/reports/".$filename;

            unlink($realFile);

        }

        $report->delete();

        //刪除附檔


        return redirect()->route('mornings.show',$morning_id);
    }
    public function addFile(Request $request)
    {

        if ($request->hasFile('upload')) {
            $attributes = $request->all();

            $files = $request->file('upload');

            $folder = 'reports/'.date('Ymd');
            foreach($files as $file) {
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];
                if ($info['size'] > 5100000) {


                } else {


                    $filename = $info['original_filename'];
                    $file->storeAs('public/' . $folder, $filename);

                    $attributes['name'] = date('Ymd') . '&' . $filename;

                    Mfile::create($attributes);
                }
            }
        }


        return redirect()->route('mornings.show',$request->input('morning_id'));
    }
    public function delMfile(Mfile $mfile)
    {
        $report_id = $mfile->report_id;
        $morning_id = $mfile->report->morning->id;
        $filename = str_replace("&","/",$mfile->name);

        $realFile = "../storage/app/public/reports/".$filename;

        unlink($realFile);

        $mfile->delete();



        $data = [
            'report_id' => $report_id,
            'morning_id' => $morning_id,
        ];
        return redirect()->route('reports.edit',$data);
    }
}
