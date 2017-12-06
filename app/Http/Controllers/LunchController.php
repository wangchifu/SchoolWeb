<?php

namespace App\Http\Controllers;

use App\Fun;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchSetup;
use App\LunchStuDate;
use App\LunchTeaDate;
use App\SemesterStudent;
use App\User;
use App\YearClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        $has_class_tea = "";

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
            //是不是導師
            $has_class_tea = $this->has_class_tea($semester);
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
            "has_class_tea"=>$has_class_tea,
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

        $order_id_array = $this->get_order_id_array($request->input('semester'));

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
                $order_id_array = $this->get_order_id_array($request->input('semester'));
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
                    if($request->input('place')=="班級教室"){
                        $att['place'] = $request->input('classroom');
                    }else{
                        $att['place'] = $request->input('place');
                    }
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
            case "change_tea";
                if(empty(($request->input('user_id')))){
                    $words = "你沒有選擇老師！";
                    return view('errors.errors', compact('words'));
                }

                $tea_order_data = LunchTeaDate::where('user_id','=',$request->input('user_id'))->where('semester','=',$request->input('semester'))->first();
                if($tea_order_data){
                    if(substr($request->input('change'),0,9) == 'eat_style'){
                        $att['eat_style'] = substr($request->input('change'),-1);
                    }else{
                        $att['place'] = $request->input('change');
                    }
                    $g_order_date =  str_replace('-','',$request->input('g_order_date'));
                    $order_dates = $this->get_order_dates($request->input('semester'));
                    foreach($order_dates as $k=>$v){
                        $order_date = str_replace('-','',$k);
                        if($order_date >= $g_order_date){
                            LunchTeaDate::where('user_id','=',$request->input('user_id'))->where('order_date','=',$k)->update($att);
                        }
                    }
                    return redirect()->route('lunch.special');
                }else{
                    $words = "該師無訂餐記錄！";
                    return view('errors.errors', compact('words'));
                }

                break;

        }
    }


    public function report(Request $request)
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

        $data =[
            'semester'=>$semester,
            'semesters'=>$semesters
        ];

        return view('lunch.report',$data);
    }

    public function report_tea1(Request $request)
    {
        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(empty($check)) return view('errors.not_admin');

        $orders = $this->get_order_id_array($request->input('semester'));
        $this_mon = date('Y-m');
        $this_order_id = $orders[$this_mon];
        //選取的月份id
        $order_id = (empty($request->input('order_id')))?$this_order_id:$request->input('order_id');

        $orders = array_flip($orders);
        //選取的月份
        $mon = $orders[$order_id];

        $o_order_dates = $this->get_order_dates($request->input('semester'));
        $i = 0;
        //訂餐日期array
        foreach($o_order_dates as $k=>$v){
            if(substr($k,0,7) == $mon and $v == 1){
                $order_dates[$i] = $k;
                $i++;
            }
        }
        //訂餐者資料
        $user_datas = [];
        $order_datas = LunchTeaDate::where('lunch_order_id','=',$order_id)->get();
        foreach($order_datas as $order_data){
            $user_datas[$order_data->user->name][$order_data->order_date]['enable'] = $order_data->enable;
            $user_datas[$order_data->user->name][$order_data->order_date]['eat_style'] = $order_data->eat_style;
            $user_datas[$order_data->user->name][$order_data->order_date]['place'] = $order_data->place;
        }

        $data = [
            'this_order_id'=>$this_order_id,
            'mon'=>$mon,
            'orders'=>$orders,
            'semester'=>$request->input('semester'),
            'order_dates'=>$order_dates,
            'user_datas'=>$user_datas,
        ];
        return view('lunch.report_tea1',$data);
    }

    public function report_tea2(Request $request)
    {
        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(empty($check)) return view('errors.not_admin');

        $order_datas = LunchTeaDate::where('semester','=',$request->input('semester'))->get();
        $i = 0;
        $last_user = "";
        $user_datas = [];
        foreach($order_datas as $order_data){
            if($order_data->enable == "eat") {
                if($last_user != $order_data->user->name) $i=0;
                $i++;
                $user_datas[$order_data->user->name] = $i;
                $last_user = $order_data->user->name;
            }
        }
        $setups = $this->get_setup();

        $data = [
            'semester'=>$request->input('semester'),
            'user_datas'=>$user_datas,
            'tea_money'=>$setups[$request->input('semester')]['tea_money'],
        ];
        return view('lunch.report_tea2',$data);
    }

    public function report_tea2_print(Request $request)
    {
        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(empty($check)) return view('errors.not_admin');
        $order_datas = LunchTeaDate::where('semester','=',$request->input('semester'))->get();
        $i = 0;
        $last_user = "";
        $last_mon = "";
        foreach($order_datas as $order_data){
            if($order_data->enable == "eat") {
                if($last_user != $order_data->user->name or $last_mon != substr($order_data->order_date,0,7)) $i=0;
                $i++;
                $user_datas[$order_data->user->name][substr($order_data->order_date,0,7)] = $i;
                $last_user = $order_data->user->name;
                $last_mon = substr($order_data->order_date,0,7);
            }
        }
        $setups = $this->get_setup();
        $data = [
            'user_datas'=>$user_datas,
            'tea_money'=>$setups[$request->input('semester')]['tea_money'],
        ];
        return view('lunch.report_tea2_print',$data);

    }

    public function report_stu1(Request $request)
    {
        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(empty($check)) return view('errors.not_admin');
        $semester = $request->input('semester');

        $order_dates = $this->get_order_dates($semester);
        foreach($order_dates as $k=>$v){
            if($v == 1) $select_date_menu[$k] = $k;
        }
        $select_date = (empty($request->input('select_date')))?current($select_date_menu):$request->input('select_date');


        $class_orders_dates = LunchStuDate::where('semester','=',$request->input('semester'))->orderBy('class_id');



        $class_orders = $class_orders_dates->where('order_date','=',$select_date)->get();

        $last_class = "";

        foreach($class_orders as $class_order){

            $class_id = $class_order->class_id;
            $order_date = $class_order->order_date;
            $eat_style = $class_order->eat_style;
            $p_id = $class_order->p_id;
            $sex = $class_order->student->sex;

            if($class_id != $last_class){
                $g = 0;
                $w = 0;
                $n = 0;
                $gb = 0;
                $gg = 0;
                $wb = 0;
                $wg = 0;
                $nb = 0;
                $ng = 0;
                $w201 = 0;
                $w202 = 0;
                $w203 = 0;
                $w204 = 0;
                $w205 = 0;
                $w206 = 0;
                $w207 = 0;
                $w208 = 0;
                $w209 = 0;
                $w210 = 0;
                $w201b =0;
                $w201g =0;
                $w202b =0;
                $w202g =0;
                $w203b =0;
                $w203g =0;
                $w204b =0;
                $w204g =0;
                $w205b =0;
                $w205g =0;
                $w206b =0;
                $w206g =0;
                $w207b =0;
                $w207g =0;
                $w208b =0;
                $w208g =0;
                $w209b =0;
                $w209g =0;
                $w210b =0;
                $w210g =0;
            }


            if($p_id > 200 and $eat_style !=3){
                $w++;
                $order_data[$class_id][$order_date]['w'] = $w;
                if($sex == 1){
                    $wb++;
                    $order_data[$class_id][$order_date]['wb'] = $wb;
                }else{
                    $wg++;
                    $order_data[$class_id][$order_date]['wg'] = $wg;
                }
                if($p_id == 201) {
                    $w201++;
                    $order_data[$class_id][$order_date]['w201'] = $w201;
                    if($sex == 1){
                        $w201b++;
                        $order_data[$class_id][$order_date]['w201b'] = $w201b;
                    }else{
                        $w201g++;
                        $order_data[$class_id][$order_date]['w201g'] = $wg;
                    }
                }elseif($p_id == 202){
                    $w202++;
                    $order_data[$class_id][$order_date]['w202'] = $w202;
                    if($sex == 1){
                        $w202b++;
                        $order_data[$class_id][$order_date]['w202b'] = $w202b;
                    }else{
                        $w202g++;
                        $order_data[$class_id][$order_date]['w202g'] = $w202g;
                    }
                }elseif($p_id == 203){
                    $w203++;
                    $order_data[$class_id][$order_date]['w203'] = $w203;
                    if($sex == 1){
                        $w203b++;
                        $order_data[$class_id][$order_date]['w203b'] = $w203b;
                    }else{
                        $w203g++;
                        $order_data[$class_id][$order_date]['w203g'] = $w203g;
                    }
                }elseif($p_id == 204){
                    $w204++;
                    $order_data[$class_id][$order_date]['w204'] = $w204;
                    if($sex == 1){
                        $w204b++;
                        $order_data[$class_id][$order_date]['w204b'] = $w204b;
                    }else{
                        $w204g++;
                        $order_data[$class_id][$order_date]['w204g'] = $w204g;
                    }
                }elseif($p_id == 205){
                    $w205++;
                    $order_data[$class_id][$order_date]['w205'] = $w205;
                    if($sex == 1){
                        $w205b++;
                        $order_data[$class_id][$order_date]['w205b'] = $w205b;
                    }else{
                        $w205g++;
                        $order_data[$class_id][$order_date]['w205g'] = $w205g;
                    }
                }elseif($p_id == 206){
                    $w206++;
                    $order_data[$class_id][$order_date]['w206'] = $w206;
                    if($sex == 1){
                        $w206b++;
                        $order_data[$class_id][$order_date]['w206b'] = $w206b;
                    }else{
                        $w206g++;
                        $order_data[$class_id][$order_date]['w206g'] = $w206g;
                    }
                }elseif($p_id == 207){
                    $w207++;
                    $order_data[$class_id][$order_date]['w207'] = $w207;
                    if($sex == 1){
                        $w207b++;
                        $order_data[$class_id][$order_date]['w207b'] = $w207b;
                    }else{
                        $w207g++;
                        $order_data[$class_id][$order_date]['w207g'] = $w207g;
                    }
                }elseif($p_id == 208){
                    $w208++;
                    $order_data[$class_id][$order_date]['w208'] = $w208;
                    if($sex == 1){
                        $w208b++;
                        $order_data[$class_id][$order_date]['w208b'] = $w208b;
                    }else{
                        $w208g++;
                        $order_data[$class_id][$order_date]['w208g'] = $w208g;
                    }
                }elseif($p_id == 209){
                    $w209++;
                    $order_data[$class_id][$order_date]['w209'] = $w209;
                    if($sex == 1){
                        $w209b++;
                        $order_data[$class_id][$order_date]['w209b'] = $w209b;
                    }else{
                        $w209g++;
                        $order_data[$class_id][$order_date]['w209g'] = $w209g;
                    }
                }elseif($p_id == 210){
                    $w210++;
                    $order_data[$class_id][$order_date]['w210'] = $w210;
                    if($sex == 1){
                        $w210b++;
                        $order_data[$class_id][$order_date]['w210b'] = $w210b;
                    }else{
                        $w210g++;
                        $order_data[$class_id][$order_date]['w210g'] = $w210g;
                    }
                }

            }elseif($p_id == 101 and $eat_style !=3){
                $g++;
                $order_data[$class_id][$order_date]['g'] = $g;
                if($sex == 1){
                    $gb++;
                    $order_data[$class_id][$order_date]['gb'] = $gb;
                }else{
                    $gg++;
                    $order_data[$class_id][$order_date]['gg'] = $gg;
                }
            }elseif($eat_style ==3){
                $n++;
                $order_data[$class_id][$order_date]['n'] = $n;
                if($sex == 1){
                    $nb++;
                    $order_data[$class_id][$order_date]['nb'] = $nb;
                }else{
                    $ng++;
                    $order_data[$class_id][$order_date]['ng'] = $ng;
                }
            }

            $last_class = $class_id;

        }

        $data = [
            'semester'=>$semester,
            'select_date'=>$select_date,
            'select_date_menu'=>$select_date_menu,
            'order_data'=>$order_data,
        ];
        return view('lunch.report_stu1',$data);

    }
    public function report_stu2(Request $request)
    {
        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(empty($check)) return view('errors.not_admin');
        $semester = $request->input('semester');
        $order_id_array = $this->get_order_id_array($semester);
        $lunch_orders = array_flip($order_id_array);
        $lunch_order_id = (empty($request->input('select_order_id')))?$order_id_array[substr(date('Y-m'),0,7)]:$request->input('select_order_id');


        $order_dates = $this->get_order_dates($semester);
        $i = 0;
        foreach($order_dates as $k=>$v){
            if($v==1 and substr($k,0,7) == $lunch_orders[$lunch_order_id]){
                $this_order_dates[$i] = $k;
                $i++;
            }
        }

        $stu_order_datas = LunchStuDate::where('lunch_order_id','=',$lunch_order_id)->orderBy('class_id')->orderBy('order_date')->get();
        $last_class = "";
        $last_date = "";

        foreach($stu_order_datas as $stu_order_data){
            if($last_class != $stu_order_data->class_id or $last_date != $stu_order_data->order_date){
                $g = 0;
                $w = 0;
            }
            if($stu_order_data->p_id > 200 and $stu_order_data->eat_style != 3 and $stu_order_data->enable == "eat") {
                $w++;
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['w'] = $w;
            }elseif($stu_order_data->p_id == 101 and $stu_order_data->eat_style != 3 and $stu_order_data->enable == "eat"){
                $g++;
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['g'] = $g;
            }
            $last_class = $stu_order_data->class_id;
            $last_date = $stu_order_data->order_date;
        }



        $data = [
            'semester'=>$semester,
            'lunch_orders'=>$lunch_orders,
            'lunch_order_id'=>$lunch_order_id,
            'this_order_dates'=>$this_order_dates,
            'order_data'=>$order_data,
        ];
        return view('lunch.report_stu2',$data);
    }

    public function stu(Request $request)
    {
        $is_tea ="";
        $is_admin = "";
        $class_id = "";
        $year_class_id = "";
        $select_date_menu = [];

        //查目前學期
        $y = date('Y') - 1911;
        $array1 = array(8,9,10,11,12,1);
        $array2 = array(2,3,4,5,6,7);
        if(in_array(date('n'),$array1)){
            if(date('n') == 1){
                $semester = ($y-1)."1";
            }else{
                $semester = $y."1";
            }
        }else{
            $semester = ($y-1)."2";
        }


        if(auth()->user()->group_id =="4" or auth()->user()->group_id2 =="4"){
            $year_class_data = YearClass::where('semester','=',$semester)->where('user_id','=',auth()->user()->id)->first();
            if($year_class_data) {
                $is_tea = $year_class_data->name;
                $year_class_id = $year_class_data->id;
                $class_id = $year_class_data->year_class;
            }
        }else{
            $is_tea = "0";
        }

        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(!empty($check)){
            $is_admin = 1;
            if(empty($request->input('select_class'))){
                $is_tea = "請選擇班級";
                $year_class_id = "";
            }else{
                $year_class_data = YearClass::where('semester','=',$semester)->where('year_class','=',$request->input('select_class'))->first();
                if($year_class_data) {
                    $is_tea = $year_class_data->name;
                    $year_class_id =  $year_class_data->id;
                    $class_id = $year_class_data->year_class;
                }else{
                    die('查無班級資料');
                }
            }
        }else{
            $is_admin = "0";
        }

        if($is_tea == "0" and $is_admin == "0"){
            $words = " 你沒有權限來這裡！";
            return view('errors.errors',compact('words'));
        }
        if($year_class_id){
            $stu_datas = SemesterStudent::where('year_class_id', '=', $year_class_id)->where('at_school','=','1')->orderBy('num')->get();
            foreach ($stu_datas as $stu) {
                $stu_data[$stu->num]['name'] = $stu->student->name;
                $stu_data[$stu->num]['sex'] = $stu->student->sex;
                $stu_data[$stu->num]['id'] = $stu->student->id;
            }
        }else{
            $stu_data=[];
        }


        //檢查某班有無訂餐了
        $class_orders = LunchStuDate::where('semester','=',$semester)->where('class_id','=',$class_id)->get();
        if(empty($class_orders->first())){
            $has_order = "";
            $order_data = [];
            $select_date = "";
        }else{
            $has_order = "1";
            foreach($class_orders as $class_order){
                $order_data[$class_order->order_date][$class_order->student_id]['eat_style'] = $class_order->eat_style;
                $order_data[$class_order->order_date][$class_order->student_id]['p_id'] = $class_order->p_id;
                $order_data[$class_order->order_date][$class_order->student_id]['enable'] = $class_order->enable;
            }
            //$select_date = (empty($request->input('select_date')))?$class_orders->first()->order_date:$request->input('select_date');
            $order_dates = $this->get_order_dates($semester);
            foreach($order_dates as $k=>$v){
                if($v == 1) $select_date_menu[$k] = $k;
            }
            $select_date = (empty($request->input('select_date')))?current($select_date_menu):$request->input('select_date');
        }

        $data = [
            'semester'=>$semester,
            'is_tea'=>$is_tea,
            'class_id'=>$class_id,
            'is_admin'=>$is_admin,
            'stu_data'=>$stu_data,
            'has_order'=>$has_order,
            'order_data'=>$order_data,
            'select_date'=>$select_date,
            'select_date_menu'=>$select_date_menu,
        ];


        return view('lunch.stu',$data);
    }

    public function stu_store(Request $request)
    {
        $semester = $request->input('semester');
        $eat_style = $request->input('eat_style');
        $p_id = $request->input('p_id');

        //這個學期各餐期的id
        $order_id_array = $this->get_order_id_array($semester);
        $order_dates = $this->get_order_dates($semester);

        $year_calss = YearClass::where('semester','=',$semester)->where('year_class','=',$request->input('class_id'))->first();

        foreach($order_dates as $k=>$v) {
            foreach ($year_calss->semester_students as $semester_student) {
                $att['order_date'] = $k;
                if($v == "0") $att['enable'] = "not";
                if($v == "1") $att['enable'] = "eat";
                $att['semester'] = $semester;
                $att['lunch_order_id'] = $order_id_array[substr($k,0,7)];
                $att['student_id'] = $semester_student->student_id;
                $att['class_id'] = $request->input('class_id');
                $att['p_id'] = $p_id[$semester_student->student_id];
                $att['eat_style'] = $eat_style[$semester_student->student_id];
                if($att['eat_style']=="3") $att['enable'] = "no_eat";

                LunchStuDate::create($att);
            }
        }
        return redirect()->route('lunch.stu');
    }

    public function stu_cancel(Request $request)
    {
        if(Input::get('do') == "1"){
            $student_id = Input::get('student_id');
            $order_date = Input::get('order_date');

            $stu_lunch_data = LunchStuDate::where('student_id','=',$student_id)->where('order_date','=',$order_date)->first();
            $att['enable'] = Input::get('enable');
            $stu_lunch_data->update($att);
            $data = [
                'class_id'=>Input::get('class_id'),
                'lunch_order_id'=>Input::get('lunch_order_id'),
            ];
            return redirect()->route('lunch.stu_cancel',$data);
        }

        $is_tea ="";
        $is_admin = "";
        $class_id = "";
        $year_class_id = "";

        //查目前學期
        $y = date('Y') - 1911;
        $array1 = array(8,9,10,11,12,1);
        $array2 = array(2,3,4,5,6,7);
        if(in_array(date('n'),$array1)){
            if(date('n') == 1){
                $semester = ($y-1)."1";
            }else{
                $semester = $y."1";
            }
        }else{
            $semester = ($y-1)."2";
        }

        $order_id_array = $this->get_order_id_array($semester);
        $lunch_orders = array_flip($order_id_array);
        $order_id = (empty($request->input('select_order_id')))?Input::get('lunch_order_id'):$request->input('select_order_id');
        $lunch_order_id = (empty($order_id))?$order_id_array[substr(date('Y-m'),0,7)]:$order_id;


        if(auth()->user()->group_id =="4" or auth()->user()->group_id2 =="4"){
            $year_class_data = YearClass::where('semester','=',$semester)->where('user_id','=',auth()->user()->id)->first();
            if($year_class_data) {
                $is_tea = $year_class_data->name;
                $year_class_id = $year_class_data->id;
                $class_id = $year_class_data->year_class;
            }
        }else{
            $is_tea = "0";
        }

        $check = Fun::where('type','=','3')->where('username','=',auth()->user()->username)->first();
        if(!empty($check)){
            $is_admin = 1;
            if(empty($request->input('select_class')) and empty(Input::get('class_id'))){
                $is_tea = "請選擇班級";
                $year_class_id = "";
            }else{
                $select_class = (empty($request->input('select_class')))?Input::get('class_id'):$request->input('select_class');
                $year_class_data = YearClass::where('semester','=',$semester)->where('year_class','=',$select_class)->first();
                if($year_class_data) {
                    $is_tea = $year_class_data->name;
                    $year_class_id =  $year_class_data->id;
                    $class_id = $year_class_data->year_class;
                }else{
                    die('查無班級資料');
                }
            }
        }else{
            $is_admin = "0";
        }

        if($is_tea == "0" and $is_admin == "0"){
            $words = " 你沒有權限來這裡！";
            return view('errors.errors',compact('words'));
        }


        if($year_class_id){
            $stu_datas = SemesterStudent::where('year_class_id', '=', $year_class_id)->where('at_school','=','1')->orderBy('num')->get();
            foreach ($stu_datas as $stu) {
                $stu_data[$stu->num]['name'] = $stu->student->name;
                $stu_data[$stu->num]['sex'] = $stu->student->sex;
                $stu_data[$stu->num]['id'] = $stu->student->id;
            }
        }else{
            $stu_data=[];
        }


        //檢查某班有無訂餐了
        $class_orders = LunchStuDate::where('lunch_order_id','=',$lunch_order_id)->where('class_id','=',$class_id)->get();

        if(empty($class_orders->first())){
            $has_order = "";
            $order_data = [];
            $this_order_dates = [];

        }else{
            $has_order = "1";

            //查該餐期供餐日期
            $order_dates = $this->get_order_dates($semester);
            $i = 0;
            foreach($order_dates as $k=>$v){
                if($v==1 and substr($k,0,7) == $lunch_orders[$lunch_order_id]){
                    $this_order_dates[$i] = $k;
                    $i++;
                }
            }


            foreach($class_orders as $class_order){
                $order_data[$class_order->student_id][$class_order->order_date]['eat_style'] = $class_order->eat_style;
                $order_data[$class_order->student_id][$class_order->order_date]['enable'] = $class_order->enable;
            }

            $order_dates = $this->get_order_dates($semester);
            foreach($order_dates as $k=>$v){
                if($v == 1) $select_date_menu[$k] = $k;
            }

        }


        $data = [
            'semester'=>$semester,
            'lunch_orders'=>$lunch_orders,
            'lunch_order_id'=>$lunch_order_id,
            'is_tea'=>$is_tea,
            'class_id'=>$class_id,
            'is_admin'=>$is_admin,
            'stu_data'=>$stu_data,
            'has_order'=>$has_order,
            'order_data'=>$order_data,
            'this_order_dates'=>$this_order_dates,
        ];

        return view('lunch.stu_cancel',$data);

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
    public function get_order_id_array($semester)
    {
        $order_id_array = [];
        $orders = LunchOrder::where('semester','=',$semester)->orderBy('id')->get();
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

    //取某一學期的每一天的供餐與否
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
    //是不是導師
    public function has_class_tea($semester)
    {
        $user_data = User::where('id','=',auth()->user()->id)->first();
        if($user_data->group_id == "4" or $user_data->group_id2 == "4"){
            $tea_data = YearClass::where('semester','=',$semester)->where('user_id','=',auth()->user()->id)->first();
            if($tea_data) {
                return $tea_data->year_class;
            }else{
                dd('沒有設定任教班級！');
            }
        }else{
            return "";
        }
    }


}
