<?php

namespace App\Http\Controllers;

use App\Fun;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchSetup;
use App\LunchTeaDate;
use Carbon\Carbon;
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
        $user_has_order = "0";
        $user_place = "";
        $user_eat_style="";
        $tea_dates = [];
        $tea_eat_styles = [];
        $tea_count_semesters = [];

        //查該使用者屆年費用
        $tea_semesters = LunchSetup::orderBy('id')->get();
        $setups = $this->get_setup();
        foreach($tea_semesters as $tea_semester){
            $count_tea_orders = $this->get_tea_orders(auth()->user()->id,$tea_semester->semester);
            $tea_count_semesters[$tea_semester->semester] = $count_tea_orders;
        }




        if($semester) {
            $tea_order = LunchTeaDate::where('user_id','=',auth()->user()->id)->where('semester','=',$semester)->first();
            if(!empty($tea_order)) {
                $user_has_order = ($tea_order->id) ? "1" : "0";
                $user_place = $tea_order->place;
                $user_eat_style = $tea_order->eat_style;
            }

            //訂過餐了
            if($user_has_order == "1"){
                $tea_dates = $this->get_user_order_date($semester);
                $tea_eat_styles = $this->get_user_eat_style($semester);
            }else{
                $tea_dates = $order_dates;
            }
        }


        $semesters = LunchSetup::orderBy('id')->pluck('semester', 'semester')->toArray();

        $data = [
            "semester"=>$semester,
            "semesters"=>$semesters,
            "semester_dates"=>$semester_dates,
            "order_dates"=>$order_dates,
            "tea_dates"=>$tea_dates,
            "tea_eat_styles"=>$tea_eat_styles,
            "user_has_order"=>$user_has_order,
            "tea_count_semesters"=>$tea_count_semesters,
            "setups"=>$setups,
            "user_place"=>$user_place,
            "user_eat_style"=>$user_eat_style,
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
        LunchTeaDate::where('semester','=',$semester)->delete();
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
        $semester_dates = $this->get_semester_dates($request->input('semester'));

        $last_name = "";
        foreach($semester_dates as $k1=>$v1){
            foreach($v1 as $k2=>$v2){
                $att['name'] = substr($v2,0,7);
                if($att['name'] != $last_name){
                    $att['semester'] = $request->input('semester');
                    $att['enable'] = 1;
                    $lunch_order = LunchOrder::create($att);
                }
                $last_name = $att['name'];
                $att2['order_date'] = $v2;
                if(!empty($order_date[$v2])){
                    $att2['enable'] = "1";
                }else{
                    $att2['enable'] = "0";
                }
                $att2['semester'] = $request->input('semester');
                $att2['lunch_order_id'] = $lunch_order->id;
                LunchOrderDate::create($att2);
            }
        }
        return redirect()->route('lunch.setup');
    }

    public function store_tea_date(Request $request)
    {
        $order_dates = $this->get_order_dates($request->input('semester'));
        $tea_order_date = $request->input('order_date');
        $att['semester'] = $request->input('semester');
        $att['place'] = $request->input('place');
        $att['factory'] = $request->input('factory');
        $att['eat_style'] = $request->input('eat_style');
        $att['semester'] = $request->input('semester');
        $att['user_id'] = auth()->user()->id;

        $order_id_array = $this->get_order_id_array();

        foreach($order_dates as $k=>$v){
            $att['order_date'] = $k;
            $att['lunch_order_id'] = $order_id_array[substr($k,0,7)];
            if($v==1){
                if(!empty($tea_order_date[$k])) {
                    $att['enable'] = "eat";
                }else{
                    $att['enable'] = "no_eat";
                }
            }else{
                $att['enable'] = "no";
            }
            LunchTeaDate::create($att);

        }
        return redirect()->route('lunch.index');
    }
    public function del_tea_date(Request $request)
    {
        $setups = $this->get_setup();
        $order_date = $request->input('del_tea_date');
        $semester = $request->input('semester');

        $dt = Carbon::now();
        $die_date = $dt->addDays($setups[$semester]['die_line'])->toDateString();
        $first_date = str_replace ("-","",$order_date);
        $second_date = str_replace ("-","",$die_date);

        if($first_date < $second_date){
            die($order_date.'當日已經無法做變更！ <button class="btn btn-default" onclick="history.back()">返回</button>');
        }

        $tea_date = LunchTeaDate::where('order_date','=',$order_date)->first();
        if(!empty($tea_date)){
            if($tea_date->enable == "no_eat"){
                die($order_date.' 你當天本來就取消訂餐了！<button class="btn btn-default" onclick="history.back()">返回</button>');
            }
            if($tea_date->enable == "no"){
                die($order_date.' 當天沒有供餐！<button class="btn btn-default" onclick="history.back()">返回</button>');
            }
        }else{
            die($order_date.' 這天沒有供餐資料！<button class="btn btn-default" onclick="history.back()">返回</button>');
        }


        $att['enable'] = "no_eat";
        LunchTeaDate::where('order_date','=',$order_date)->update($att);
        return redirect()->route('lunch.index');
    }

    public function stu()
    {
        if(auth()->user()->group_id =="4" or auth()->user()->group_id2 =="4"){
            $is_tea = 1;
        }else{
            $is_tea = 0;
        }

        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(!empty($check)){
            $is_admin = 1;
        }else{
            $is_admin = 0;
        }

        if($is_tea == 0 and $is_admin == 0){
            die(' 你沒有權限來這裡！<button class="btn btn-default" onclick="history.back()">返回</button>');
        }



        return view('lunch.stu');
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
    //取餐期id
    public function get_order_id_array()
    {
        $order_id_array = [];
        $orders = LunchOrder::orderBy('id')->get();
        foreach($orders as $order){
            $order_id_array[$order->name] =$order->id;
        }
        return $order_id_array;
    }

    public function get_user_order_date($semester)
    {
        $user_order_date = [];
        $tea_order_dates = LunchTeaDate::where('semester','=',$semester)->where('user_id','=',auth()->user()->id)->get();
        if($tea_order_dates) {
            foreach ($tea_order_dates as $k => $v) {
                $user_order_dates[$v->order_date] = $v->enable;
            }
        }

        return $user_order_dates;
    }
    public function get_user_eat_style($semester)
    {
        $user_eat_style = [];
        $tea_eat_styles = LunchTeaDate::where('semester','=',$semester)->where('user_id','=',auth()->user()->id)->get();
        if($tea_eat_styles) {
            foreach ($tea_eat_styles as $k => $v) {
                $user_eat_styles[$v->order_date] = $v->eat_style;
            }
        }

        return $user_eat_styles;
    }

    public function get_order_dates($semester)
    {
        $order_dates=[];
        $lunch_order_dates = LunchOrderDate::where('semester','=',$semester)->get();
        if($lunch_order_dates) {
            foreach ($lunch_order_dates as $k => $v) {
                $order_dates[$v->order_date] = $v->enable;
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

    //查學期設定
    public function get_setup()
    {
        $setups = LunchSetup::orderBy('id')->get();
        $lunch_setup = [];
        foreach($setups as $setup){
            $lunch_setup[$setup->semester]['tea_money'] = $setup->tea_money;
            $lunch_setup[$setup->semester]['stud_money'] = $setup->stud_money;
            $lunch_setup[$setup->semester]['stud_back_money'] = $setup->stud_back_money;
            $lunch_setup[$setup->semester]['support_part_money'] = $setup->support_part_money;
            $lunch_setup[$setup->semester]['support_all_money'] = $setup->support_all_money;
            $lunch_setup[$setup->semester]['die_line'] = $setup->die_line;
            $lunch_setup[$setup->semester]['place'] = $setup->place;
            $lunch_setup[$setup->semester]['factory'] = $setup->factory;
            $lunch_setup[$setup->semester]['stud_gra_date'] = $setup->stud_gra_date;
        }
        return $lunch_setup;
    }

    //查老師某一學期訂餐數
    public function get_tea_orders($user_id,$semester)
    {
        $count_tea_orders = LunchTeaDate::where('semester','=',$semester)
            ->where('user_id','=',$user_id)
            ->where('enable','=','eat')
            ->count();
        return $count_tea_orders;
    }


}
