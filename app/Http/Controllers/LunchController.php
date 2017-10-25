<?php

namespace App\Http\Controllers;

use App\Fun;
use App\LunchSetup;
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
    public function index()
    {
        $semester = "";
        return view('lunch.index',compact('semester'));
    }
    public function setup()
    {
        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(empty($check)) return view('errors.not_admin');


        $lunch_setups = LunchSetup::orderBy('id')->get();

        return view('lunch.setup',compact('lunch_setups'));
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
        $lunch_setup->delete();
        return redirect()->route('lunch.setup');
    }
    public function create_order($semester)
    {
        $this_year = substr($semester,0,3)+1911;
        $this_seme = substr($semester,-1,1);
        $next_year = $this_year +1 ;
        if($this_seme == 1){
            $month_array = ["八月"=>$this_year."-08","九月"=>$this_year."-09","十月"=>$this_year."-10","十一月"=>$this_year."-11","十二月"=>$this_year."-12","一月"=>$next_year."-01"];
        }else{
            $month_array = ["二月"=>$this_year."-02","三月"=>$this_year."-03","四月"=>$this_year."-04","五月"=>$this_year."-05","六月"=>$this_year."-06"];
        }
        ;

        foreach($month_array as $k => $v) {
            $semester_order[$k] = $this->get_date($v);
        }


        $data = [
            "semester" => $semester,
            "month_array"=>$month_array,
            "semester_order"=>$semester_order,
        ];

        return view('lunch.setup',$data);
    }

    public function store_order(Request $request)
    {
        print_r($request->all());
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
