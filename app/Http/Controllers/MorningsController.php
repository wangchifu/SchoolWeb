<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Mfile;
use App\Morning;
use App\Report;
use Illuminate\Http\Request;

class MorningsController extends Controller
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
    public function index(Request $request)
    {
        //查詢最新會議，並分頁
        $mornings = Morning::orderBy('name', 'DESC')->paginate(10);
        //同 $data = ["mornings"=>$mornings];
        $data = compact('mornings');

        return view('mornings.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Morning::class);
        $year = [
            '2017'=>'2017 年',
            '2018'=>'2018 年',
            '2019'=>'2019 年',
            '2020'=>'2020 年',
            '2021'=>'2021 年',
            '2022'=>'2022 年',
            '2023'=>'2023 年',
            '2024'=>'2024 年',
            '2025'=>'2025 年',
            '2026'=>'2026 年',
            '2027'=>'2027 年',
        ];
        $month = [
            '01'=>'01 月',
            '02'=>'02 月',
            '03'=>'03 月',
            '04'=>'04 月',
            '05'=>'05 月',
            '06'=>'06 月',
            '07'=>'07 月',
            '08'=>'08 月',
            '09'=>'09 月',
            '10'=>'10 月',
            '11'=>'11 月',
            '12'=>'12 月',
        ];
        $day = [
            '01'=>'01 日',
            '02'=>'02 日',
            '03'=>'03 日',
            '04'=>'04 日',
            '05'=>'05 日',
            '06'=>'06 日',
            '07'=>'07 日',
            '08'=>'08 日',
            '09'=>'09 日',
            '10'=>'10 日',
            '11'=>'11 日',
            '12'=>'12 日',
            '13'=>'13 日',
            '14'=>'14 日',
            '15'=>'15 日',
            '16'=>'16 日',
            '17'=>'17 日',
            '18'=>'18 日',
            '19'=>'19 日',
            '20'=>'20 日',
            '21'=>'21 日',
            '22'=>'22 日',
            '23'=>'23 日',
            '24'=>'24 日',
            '25'=>'25 日',
            '26'=>'26 日',
            '27'=>'27 日',
            '28'=>'28 日',
            '29'=>'29 日',
            '30'=>'30 日',
            '31'=>'31 日',
        ];
        $week = [
            '(一)'=>'星期一',
            '(二)'=>'星期二',
            '(三)'=>'星期三',
            '(四)'=>'星期四',
            '(五)'=>'星期五',
            '(六)'=>'星期六',
            '(日)'=>'星期日',
        ];
        $weekarray=array("(日)","(一)","(二)","(三)","(四)","(五)","(六)");


        $this_day=explode('-',date("Y-m-d"));
        $this_day[3] = $weekarray[date("w")];
        $this_day[4] = "教師晨會";
        $data = [
            'this_day' => $this_day,
            'year' =>$year,
            'month' =>$month,
            'day' =>$day,
            'week' =>$week,

        ];
        return view('mornings.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this_date = $request->input('year') ."-". $request->input('month')."-".$request->input('day');
        $this_name = $request->input('name');
        $morning = Morning::where('name','like',$this_date.'%'.$this_name)->first();
        if($morning){
            return redirect()->route('mornings.index');
        }
        $attributes = $request->all();
        $attributes['name'] = $request->input('year') ."-". $request->input('month')."-".$request->input('day').'-'.$request->input('week')."-".$request->input('name');
        $attributes['user_id'] = auth()->user()->id;
        $attributes['who_do'] = auth()->user()->job_title." ".auth()->user()->name;
        Morning::create($attributes);

        return redirect()->route('mornings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Morning $morning)
    {
        //$data = compact('morning');
        $order_reports = array();

        foreach ($morning->reports as $report) {
            $content = str_replace('->', '<font color=red><strong>',$report->content);
            $content = str_replace('<-','</strong></font>',$content);
            $order_reports[$report->order_by]['content'] = str_replace(chr(13) . chr(10), '<br>', $content);

            $order_reports[$report->order_by]['id'] = $report->id;
            $order_reports[$report->order_by]['user_id'] = $report->user_id;

            $order_reports[$report->order_by]['who_do'] = $report->who_do;
            $order_reports[$report->order_by]['mfiles'] = $report->mfiles;

        }
        ksort($order_reports);

        //判別是否過期
        $rightNow = date("Y-m-d-H");
        if(strpos($morning->name,"教師晨會")){
            $deadLine = substr($morning->name,0,10)."-12";
            if( $deadLine < $rightNow)
            {
                $overDay =true;
            }else{
                $overDay = false;
            }
        }else{
            //校務會議可以到晚上才過期
            $deadLine = substr($morning->name,0,10)."-23";
            if( $deadLine < $rightNow)
            {
                $overDay =true;
            }else{
                $overDay = false;
            }
        }


        $data=[
            'morning'=>$morning,
            'order_reports' => $order_reports,
            'overDay'=>$overDay,
        ];
        return view('mornings.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Morning $morning)
    {
        //$this->authorize('update', $post);

        //$categories = Category::all()->pluck('name', 'id')->toArray();

        //$data = compact('morning');
        $year = [
            '2017'=>'2017 年',
            '2018'=>'2018 年',
            '2019'=>'2019 年',
            '2020'=>'2020 年',
            '2021'=>'2021 年',
            '2022'=>'2022 年',
            '2023'=>'2023 年',
            '2024'=>'2024 年',
            '2025'=>'2025 年',
            '2026'=>'2026 年',
            '2027'=>'2027 年',
        ];
        $month = [
            '01'=>'01 月',
            '02'=>'02 月',
            '03'=>'03 月',
            '04'=>'04 月',
            '05'=>'05 月',
            '06'=>'06 月',
            '07'=>'07 月',
            '08'=>'08 月',
            '09'=>'09 月',
            '10'=>'10 月',
            '11'=>'11 月',
            '12'=>'12 月',
        ];
        $day = [
            '01'=>'01 日',
            '02'=>'02 日',
            '03'=>'03 日',
            '04'=>'04 日',
            '05'=>'05 日',
            '06'=>'06 日',
            '07'=>'07 日',
            '08'=>'08 日',
            '09'=>'09 日',
            '10'=>'10 日',
            '11'=>'11 日',
            '12'=>'12 日',
            '13'=>'13 日',
            '14'=>'14 日',
            '15'=>'15 日',
            '16'=>'16 日',
            '17'=>'17 日',
            '18'=>'18 日',
            '19'=>'19 日',
            '20'=>'20 日',
            '21'=>'21 日',
            '22'=>'22 日',
            '23'=>'23 日',
            '24'=>'24 日',
            '25'=>'25 日',
            '26'=>'26 日',
            '27'=>'27 日',
            '28'=>'28 日',
            '29'=>'29 日',
            '30'=>'30 日',
            '31'=>'31 日',
        ];
        $week = [
            '(一)'=>'星期一',
            '(二)'=>'星期二',
            '(三)'=>'星期三',
            '(四)'=>'星期四',
            '(五)'=>'星期五',
            '(六)'=>'星期六',
            '(日)'=>'星期日',
        ];
        $this_day = explode("-",$morning->name);

        $data = [
            'this_day' => $this_day,
            'year' =>$year,
            'month' =>$month,
            'day' =>$day,
            'week' =>$week,
            'morning'=>$morning,
        ];


        return view('mornings.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Morning $morning)
    {

        $attributes = $request->all();
        $attributes['name'] = $request->input('year') ."-". $request->input('month')."-".$request->input('day').'-'.$request->input('week')."-".$request->input('name');

        $morning->update($attributes);

        return redirect()->route('mornings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Morning $morning)
    {
        //刪report
        $reports = Report::where('morning_id',$morning->id)->get();
        foreach($reports as $report){
            $mfiles = $report->mfiles;
            foreach($mfiles as $mfile){
                $mfile->delete();

                $filename = str_replace("&","/",$mfile->name);

                $realFile = "../storage/app/public/reports/".$filename;

                unlink($realFile);

            }

            $report->delete();
        }
        //刪morning
        $morning->delete();

        return redirect()->route('mornings.index');
    }
    /**
    public function createReport(Morning $morning)
    {
        //如果已經發表過報告的，應該是變成再修改已經發表過的
        $report = Report::where('user_id',auth()->user()->id)->where('morning_id',$morning->id)->first();

        if($report) {
            $data = [
                'report_id'=>$report->id,
                'morning_id'=>$morning->id,
            ];
            return redirect()->route('mornings.editReport',$data);
        }

        $data = compact('morning');
        return view('mornings.createReport',$data);
    }

    public function storeReport(ReportRequest $request)
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

    public function editReport(Morning $morning,$report_id)
    {

        $report = Report::find($report_id);

        if($report->user_id <> auth()->user()->id) return redirect()->route('mornings.show',$report->morning_id);

        $data = [
            'report'=>$report,
            'morning'=>$morning,
            'mfiles'=>$report->mfiles,
        ];
        return view('mornings.editReport',$data);
    }

    public function updateReport(ReportRequest $request,Report $report)
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

    public function destroyReport(Report $report)
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
     * **/
    /**
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
     **/
    public function downloadMfile($downloadMfile)
    {
        if ($downloadMfile) {
            $downloadMfile = str_replace("&","/",$downloadMfile);
            $filename = explode('/',$downloadMfile);
            $realFile = "../storage/app/public/reports/".$downloadMfile;
            header("Content-type:application");
            header("Content-Length: " .(string)(filesize($realFile)));
            header("Content-Disposition: attachment; filename=".$filename[1]);
            readfile($realFile);
        } else {
            return null;
        }
    }

    public function txtDown(Morning $morning)
    {
        $filename = $morning->name.".txt";
        $txtDown = $morning->name."\r\n";
        foreach($morning->reports as $report){
            $txt = "●".$report->who_do."\r\n".$report->content."\r\n \r\n";
            $ori[$report->order_by] = $txt;
        }
        ksort($ori);
        foreach($ori as $k=>$v){
            $txtDown .= $v;
        }
        header("Content-disposition: attachment;filename=$filename");
        header("Content-type: text/text ; Charset=utf8");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $txtDown;
    }
/**
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
        return redirect()->route('mornings.editReport',$data);
    }
 **/
}
