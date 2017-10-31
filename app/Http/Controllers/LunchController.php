<?php

namespace App\Http\Controllers;

use App\Fun;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchSetup;
use App\LunchTeaDate;
use Illuminate\Http\Request;

class LunchController extends Controller
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

        $semester = ($request->input('semester'))?$request->input('semester'):"";
        $semester_dates = $this->get_semester_dates($semester);
        $order_dates = $this->get_order_dates($semester);

        $tea_dates = $order_dates;

        $semesters = LunchSetup::orderBy('id')->pluck('semester', 'semester')->toArray();

        $data = [
            "semester"=>$semester,
            "semesters"=>$semesters,
            "semester_dates"=>$semester_dates,
            "order_dates"=>$order_dates,
            "tea_dates"=>$tea_dates,
        ];
        return view('lunch.index',$data);
    }
    public function setup()
    {
        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(empty($check)) return view('errors.not_admin');
        $lunch_setups = LunchSetup::orderBy('id')->get();
        foreach($lunch_setups as $lunch_setup){
            $has_order[$lunch_setup->semester] = LunchOrder::where('semester','=',$lunch_setup->semester)->first();
        }

        return view('lunch.setup',compact('lunch_setups','has_order'));
    }

    public function show_order($show_semester)
    {
        $lunch_setups = LunchSetup::orderBy('id')->get();
        foreach($lunch_setups as $lunch_setup){
            $has_order[$lunch_setup->semester] = LunchOrder::where('semester','=',$lunch_setup->semester)->first();
        }

        $order_dates = $this->get_order_dates($show_semester);

        $semester_dates = $this->get_semester_dates($show_semester);

        $data = [
            'lunch_setups'=>$lunch_setups,
            'has_order'=>$has_order,
            'show_semester'=>$show_semester,
            'semester_dates'=>$semester_dates,
            'order_dates'=>$order_dates,
        ];
        return view('lunch.setup',$data);
    }

    public function store_setup(Request $request)
    {
        LunchSetup::create($request->all());
        return redirect()->route('lunch.setup');
    }
    public function update_setup(LunchSetup $lunch_setup,Request $request)
    {
        $lunch_setup->update($request->all());
        return redirect()->route('lunch.setup');
    }
    public function delete_setup(LunchSetup $lunch_setup)
    {
        $semester = $lunch_setup->semester;
        $lunch_setup->delete();
        LunchOrder::where('semester','=',$semester)->delete();
        LunchOrderDate::where('semester','=',$semester)->delete();
        return redirect()->route('lunch.setup');
    }
    public function create_order($semester)
    {
        $semester_dates = $this->get_semester_dates($semester);

        $data = [
            "semester" => $semester,
            "semester_dates"=>$semester_dates,
        ];

        return view('lunch.setup',$data);
    }

    public function store_order(Request $request)
    {
        $order_date = $request->input('order_date');
        $last_name = "";
        foreach($order_date as $k=>$v){
            $att['name'] = substr($k,0,7);
            if($att['name'] != $last_name){
                 $att['semester'] = $request->input('semester');
                $att['enable'] = 1;
                $lunch_order = LunchOrder::create($att);
            }
            $last_name = $att['name'];
            $att2['order_date'] = $k;
            $att2['semester'] = $request->input('semester');
            $att2['lunch_order_id'] = $lunch_order->id;
            LunchOrderDate::create($att2);
        }
        return redirect()->route('lunch.setup');
    }

    public function store_tea_date(Request $request)
    {
        $order_date = $request->input('order_date');
        $att['semester'] = $request->input('semester');
        $att['user_id'] = auth()->user()->id;
        foreach($order_date as $k=> $v){
            $att['order_date'] = $k;
            //$att['lunch_order_id'] =
            //LunchTeaDate::create($att);
        }
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
        //
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

    public function get_order_dates($semester)
    {
        $order_dates = [];
        $lunch_order_dates = LunchOrderDate::where('semester','=',$semester)->get();
        if($lunch_order_dates) {
            foreach ($lunch_order_dates as $k => $v) {
                $order_dates[$v->order_date] = 'on';
            }
        }


        return $order_dates;
    }
    //秀某學期的每一天
    public function get_semester_dates($semester)
    {
        $this_year = substr($semester,0,3)+1911;
        $this_seme = substr($semester,-1,1);
        $next_year = $this_year +1 ;
        if($this_seme == 1){
            $month_array = ["八月"=>$this_year."-08","九月"=>$this_year."-09","十月"=>$this_year."-10","十一月"=>$this_year."-11","十二月"=>$this_year."-12","一月"=>$next_year."-01"];
        }else{
            $month_array = ["二月"=>$next_year."-02","三月"=>$next_year."-03","四月"=>$next_year."-04","五月"=>$next_year."-05","六月"=>$next_year."-06"];
        }


        foreach($month_array as $k => $v) {
            $semester_dates[$k] = $this->get_date($v);
        }
        return $semester_dates;
    }

    //秀某月的每一天
    public function get_date($year_month)
    {
        $this_date = explode("-",$year_month);
        $days=array("01"=>"31","02"=>"28","03"=>"31","04"=>"30","05"=>"31","06"=>"30","07"=>"31","08"=>"31","09"=>"30","10"=>"31","11"=>"30","12"=>"31");
        //潤年的話，二月29天
        if(checkdate(2,29,$this_date[0])){
            $days['02'] = 29;
        }else{
            $days['02'] = 28;
        }

        //$ch_w = array("0"=>"(日)","1"=>"(一)","2"=>"(二)","3"=>"(三)","4"=>"(四)","5"=>"(五)","6"=>"(六)","7"=>"(日)");


        for($i=1;$i<= $days[$this_date[1]];$i++){
             $order_date[$i] = $this_date[0]."-".$this_date[1]."-".sprintf("%02s", $i);
             $w = $this->get_w($order_date[$i]);
            //$order_date[$i] = $order_date[$i]."-".$ch_w[$w];
        }
        return $order_date;


    }
    //查星期
    public function get_w($d)
    {
        $arrDate=explode("-",$d);
        $week=date("w",mktime(0,0,0,$arrDate[1],$arrDate[2],$arrDate[0]));
        return $week;
    }

}
