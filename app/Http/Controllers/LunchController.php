<?php

namespace App\Http\Controllers;

use App\Fun;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchSetup;
use App\LunchTeaDate;
use App\User;
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

                //處理逾期不給訂
                $first = LunchOrderDate::where('semester','=',$semester)->where('enable','=','1')->orderBy('id')->first();
                if($first){
                    $die_date = str_replace('-','',$first->order_date);
                    if(date('Ymd') > $die_date){
                        $words = "你已經超過訂餐期限，忘記訂餐請洽管理者！";
                        return view('errors.errors',compact('words'));
                    }
                }

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
            $words = "當日已經無法做變更！";
            return view('errors.errors',compact('words'));
        }

        $tea_date = LunchTeaDate::where('order_date','=',$order_date)->first();
        if(!empty($tea_date)){
            if($tea_date->enable == "no_eat"){
                $words = $order_date." 你當天本來就取消訂餐了！";
                return view('errors.errors',compact('words'));
            }
            if($tea_date->enable == "no"){
                $words = $order_date." 當天沒有供餐！";
                return view('errors.errors',compact('words'));
            }
        }else{
            $words = $order_date." 這天沒有供餐資料！";
            return view('errors.errors',compact('words'));
        }


        $att['enable'] = "no_eat";
        LunchTeaDate::where('order_date','=',$order_date)->update($att);
        return redirect()->route('lunch.index');
    }

    public function special(Request $request)
    {
        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(empty($check)) return view('errors.not_admin');

        //查目前學期
        $y = date('Y') - 1911;
        $array1 = array(8,9,10,11,12,1);
        $array2 = array(2,3,4,5,6,7);
        if(in_array(date('n'),$array1)){
            if(date('n') == 1){
                $this_semester = ($y-1)."1";
            }else{
                $this_semester = $y."1";
            }
        }else{
            $this_semester = ($y-1)."2";
        }

        $semester = (empty($request->input('semester')))?$this_semester:$request->input('semester');

        $semesters = LunchSetup::all()->pluck('semester', 'semester')->toArray();
        $d = LunchSetup::where('semester','=',$semester)->first();
        $factorys_array = explode(',',$d->factory);
        $places_array = explode(',',$d->place);
        foreach($factorys_array as $factory){
            $factorys[$factory] = $factory;
        }
        foreach($places_array as $place){
            $places[$place] = $place;
        }

        $users = User::orderBy('order_by')->pluck('name', 'id')->toArray();

        $data = [
            'users'=>$users,
            'semester'=>$semester,
            'semesters'=>$semesters,
            'factorys'=>$factorys,
            'places'=>$places,
        ];



        return view('lunch.special',$data);
    }

    public function do_special(Request $request)
    {
        switch ($request->input('op')){
            case "order_tea":
                if(empty(($request->input('user_id')))){
                    $words = "你沒有選擇老師！";
                    return view('errors.errors', compact('words'));
                }


                $check_order = LunchTeaDate::where('user_id','=',$request->input('user_id'))->where('order_date','=',$request->input('b_order_date'))->first();
                if($check_order){
                    $words = "這位教職員已經有訂餐記錄！請查明！";
                    return view('errors.errors', compact('words'));
                }

                $order_dates = $this->get_order_dates($request->input('semester'));
                $order_id_array = $this->get_order_id_array();
                $b_order_date =  str_replace('-','',$request->input('b_order_date'));
                foreach($order_dates as $k=>$v){
                    $att['order_date'] = $k;
                    if($v == 0){
                        $att['enable'] = "no";
                    }elseif($v == 1){
                        $order_date = str_replace('-','',$k);
                        if($order_date < $b_order_date) {
                            $att['enable'] = "no_eat";
                        }elseif($order_date >= $b_order_date){
                            $att['enable'] = "eat";
                        }
                    }
                    $att['semester'] = $request->input('semester');
                    $att['lunch_order_id'] = $order_id_array[substr($k,0,7)];
                    $att['user_id'] = $request->input('user_id');
                    $att['place'] = $request->input('place');
                    $att['factory'] = $request->input('factory');
                    $att['eat_style'] = $request->input('eat_style');

                    LunchTeaDate::create($att);
                }
                return redirect()->route('lunch.special');

                break;
            case "cancel_tea":
                if(empty(($request->input('user_id')))){
                    $words = "你沒有選擇老師！";
                    return view('errors.errors', compact('words'));
                }
                    $tea_order_data = LunchTeaDate::where('user_id','=',$request->input('user_id'))->where('order_date','=',$request->input('c_order_date'))->first();
                    if($tea_order_data){
                        if($tea_order_data->enable == "no"){
                            $words = $request->input('c_order_date') . "該日沒有供餐！";
                            return view('errors.errors', compact('words'));
                        }
                        if($tea_order_data->enable == "no_eat" and $request->input('enable') == "no_eat"){
                            $words = $request->input('c_order_date') . "該師該日早已取消訂餐！";
                            return view('errors.errors', compact('words'));
                        }
                        if($tea_order_data->enable == "eat" and $request->input('enable') == "eat"){
                            $words = $request->input('c_order_date') . "該師該日早已有訂餐！";
                            return view('errors.errors', compact('words'));
                        }

                        $att['enable'] = $request->input('enable');

                        $tea_order_data->update($att);
                        return redirect()->route('lunch.special');

                    }else{
                        $words = "該師無此日的訂餐記錄！";
                        return view('errors.errors', compact('words'));
                    }
                break;

        }
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
            $words = $order_date." 你沒有權限來這裡！";
            return view('errors.errors',compact('words'));
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
