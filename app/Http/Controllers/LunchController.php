<?php

namespace App\Http\Controllers;

use App\Fun;
use App\LunchCheck;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchSatisfaction;
use App\LunchSatisfactionClass;
use App\LunchSetup;
use App\LunchStuDate;
use App\LunchStuOrder;
use App\LunchTeaDate;
use App\SemesterStudent;
use App\Student;
use App\User;
use App\YearClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

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
        $semester_dates = [];
        $semester = ($request->input('semester')) ? $request->input('semester') : "";

        $order_dates = $this->get_order_dates($semester);
        $user_has_order = "0";
        $user_place = "";
        $user_eat_style = "";
        $tea_dates = [];
        $tea_eat_styles = [];
        $tea_count_semesters = [];
        $has_class_tea = "";

        //查該使用者屆年費用
        $tea_semesters = LunchSetup::orderBy('id')->get();
        $setups = $this->get_setup();
        foreach ($tea_semesters as $tea_semester) {
            $count_tea_orders = $this->get_tea_orders(auth()->user()->id, $tea_semester->semester);
            $tea_count_semesters[$tea_semester->semester] = $count_tea_orders;
        }


        if ($semester) {
            $semester_dates = $this->get_semester_dates($semester);

            $tea_order = LunchTeaDate::where('user_id', '=', auth()->user()->id)->where('semester', '=', $semester)->first();
            if (!empty($tea_order)) {
                $user_has_order = ($tea_order->id) ? "1" : "0";
                $user_place = $tea_order->place;
                $user_eat_style = $tea_order->eat_style;
            }

            //訂過餐了
            if ($user_has_order == "1") {
                $tea_dates = $this->get_user_order_date($semester);
                $tea_eat_styles = $this->get_user_eat_style($semester);
            } else {

                $tea_open = $setups[$semester]['tea_open'];

                //如果沒開啟隨時可訂
                if($tea_open != "on") {
                    //處理逾期不給訂
                    $first = LunchOrderDate::where('semester', '=', $semester)->where('enable', '=', '1')->orderBy('id')->first();
                    if ($first) {
                        $die_date = str_replace('-', '', $first->order_date);
                        if (date('Ymd') > $die_date) {
                            $words = "你已經超過訂餐期限，忘記訂餐請洽管理者！";
                            return view('errors.errors', compact('words'));
                        }
                    }
                }

                $tea_dates = $order_dates;
            }
            //是不是導師
            $has_class_tea = $this->has_class_tea($semester);
        }


        $semesters = LunchSetup::orderBy('id')->pluck('semester', 'semester')->toArray();

        $data = [
            "semester" => $semester,
            "semesters" => $semesters,
            "semester_dates" => $semester_dates,
            "order_dates" => $order_dates,
            "tea_dates" => $tea_dates,
            "tea_eat_styles" => $tea_eat_styles,
            "user_has_order" => $user_has_order,
            "tea_count_semesters" => $tea_count_semesters,
            "setups" => $setups,
            "user_place" => $user_place,
            "user_eat_style" => $user_eat_style,
            "has_class_tea" => $has_class_tea,
        ];
        return view('lunch.index', $data);
    }

    public function setup()
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');
        $lunch_setups = LunchSetup::orderBy('id')->get();
        foreach ($lunch_setups as $lunch_setup) {
            $has_order[$lunch_setup->semester] = LunchOrder::where('semester', '=', $lunch_setup->semester)->first();
        }

        return view('lunch.setup', compact('lunch_setups', 'has_order'));
    }

    public function show_order($show_semester)
    {
        $lunch_setups = LunchSetup::orderBy('id')->get();
        foreach ($lunch_setups as $lunch_setup) {
            $has_order[$lunch_setup->semester] = LunchOrder::where('semester', '=', $lunch_setup->semester)->first();
        }

        $order_dates = $this->get_order_dates($show_semester);

        $semester_dates = $this->get_semester_dates($show_semester);

        $data = [
            'lunch_setups' => $lunch_setups,
            'has_order' => $has_order,
            'show_semester' => $show_semester,
            'semester_dates' => $semester_dates,
            'order_dates' => $order_dates,
        ];
        return view('lunch.setup', $data);
    }

    public function store_setup(Request $request)
    {
        LunchSetup::create($request->all());
        return redirect()->route('lunch.setup');
    }

    public function update_setup(LunchSetup $lunch_setup, Request $request)
    {
        $att['tea_money'] = $request->input('tea_money');
        $att['stud_money'] = $request->input('stud_money');
        $att['stud_back_money'] = $request->input('stud_back_money');
        $att['support_part_money'] = $request->input('support_part_money');
        $att['support_all_money'] = $request->input('support_all_money');
        $att['die_line'] = $request->input('die_line');
        $att['place'] = $request->input('place');
        $att['factory'] = $request->input('factory');
        $att['stud_gra_date'] = $request->input('stud_gra_date');
        if(empty($request->input('disable'))){
            $att['disable'] = null;
        }else{
            $att['disable'] = $request->input('disable');
        }

        if(empty($request->input('tea_open'))){
            $att['tea_open'] = null;
        }else{
            $att['tea_open'] = $request->input('tea_open');
        }

        $lunch_setup->update($att);
        return redirect()->route('lunch.setup');
    }

    public function delete_setup(LunchSetup $lunch_setup)
    {
        $semester = $lunch_setup->semester;
        $lunch_setup->delete();
        LunchOrder::where('semester', '=', $semester)->delete();
        LunchOrderDate::where('semester', '=', $semester)->delete();
        LunchTeaDate::where('semester', '=', $semester)->delete();
        LunchStuDate::where('semester', '=', $semester)->delete();
        LunchStuOrder::where('semester', '=', $semester)->delete();
        return redirect()->route('lunch.setup');
    }

    public function create_order($semester)
    {
        $semester_dates = $this->get_semester_dates($semester);

        $data = [
            "semester" => $semester,
            "semester_dates" => $semester_dates,
        ];

        return view('lunch.setup', $data);
    }

    public function store_order(Request $request)
    {
        $order_date = $request->input('order_date');
        $semester_dates = $this->get_semester_dates($request->input('semester'));

        $last_name = "";
        foreach ($semester_dates as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $att['name'] = substr($v2, 0, 7);
                if ($att['name'] != $last_name) {
                    $att['semester'] = $request->input('semester');
                    $att['enable'] = 1;
                    $lunch_order = LunchOrder::create($att);
                }
                $last_name = $att['name'];
                $att2['order_date'] = $v2;
                if (!empty($order_date[$v2])) {
                    $att2['enable'] = "1";
                } else {
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
        $att['user_id'] = auth()->user()->id;

        $order_id_array = $this->get_order_id_array($request->input('semester'));

        //避免F5
        $s_key = "store_tea".auth()->user()->id;

        $create_tea = [];
        if(!session($s_key)) {
            session([$s_key => '1']);
            foreach ($order_dates as $k => $v) {
                $att['order_date'] = $k;
                $att['lunch_order_id'] = $order_id_array[substr($k, 0, 7)];
                if ($v == 1) {
                    if (!empty($tea_order_date[$k])) {
                        $att['enable'] = "eat";
                    } else {
                        $att['enable'] = "no_eat";
                    }
                } else {
                    $att['enable'] = "no";
                }
                //LunchTeaDate::create($att);
                $new_one = [
                    'order_date'=>$att['order_date'],
                    'semester'=>$att['semester'],
                    'place'=>$att['place'],
                    'factory'=>$att['factory'],
                    'eat_style'=>$att['eat_style'],
                    'user_id'=>$att['user_id'],
                    'enable'=>$att['enable'],
                    'lunch_order_id'=>$att['lunch_order_id'],
                ];
                array_push($create_tea, $new_one);

            }
            LunchTeaDate::insert($create_tea);
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
        $first_date = str_replace("-", "", $order_date);
        $second_date = str_replace("-", "", $die_date);

        if ($first_date < $second_date) {
            $words = "當日已經無法做變更！";
            return view('errors.errors', compact('words'));
        }

        $tea_date = LunchTeaDate::where('order_date', '=', $order_date)
            ->where('user_id','=',auth()->user()->id)
            ->first();


        if (!empty($tea_date)) {
            if ($tea_date->enable == "no_eat" and $request->input('enable')=="no_eat") {
                $words = $order_date . " 你當天本來就取消訂餐了！";
                return view('errors.errors', compact('words'));
            }
            if ($tea_date->enable == "eat" and $request->input('enable')=="eat") {
                $words = $order_date . " 你當天本來就有訂餐了！";
                return view('errors.errors', compact('words'));
            }
            if ($tea_date->enable == "no") {
                $words = $order_date . " 當天沒有供餐！";
                return view('errors.errors', compact('words'));
            }
        } else {
            $words = $order_date . " 這天沒有供餐資料！";
            return view('errors.errors', compact('words'));
        }


        $att['enable'] = $request->input('enable');
        LunchTeaDate::where('order_date', '=', $order_date)
            ->where('user_id','=',auth()->user()->id)
            ->update($att);
        return redirect()->route('lunch.index');
    }

    public function special(Request $request)
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');

        //查目前學期
        $y = date('Y') - 1911;
        $array1 = array(8, 9, 10, 11, 12, 1);
        $array2 = array(2, 3, 4, 5, 6, 7);
        if (in_array(date('n'), $array1)) {
            if (date('n') == 1) {
                $this_semester = ($y - 1) . "1";
            } else {
                $this_semester = $y . "1";
            }
        } else {
            $this_semester = ($y - 1) . "2";
        }

        $semester = (empty($request->input('semester'))) ? $this_semester : $request->input('semester');

        //查新學期設好了沒
        $check_new_semester = LunchOrderDate::where('semester','=',$semester)->first();
        if(empty($check_new_semester)){
            $words = "新學期尚未設定好！";
            return view('errors.errors',compact('words'));
        }


        $semesters = LunchSetup::all()->pluck('semester', 'semester')->toArray();
        $d = LunchSetup::where('semester', '=', $semester)->first();
        $factorys_array = explode(',', $d->factory);
        $places_array = explode(',', $d->place);
        foreach ($factorys_array as $factory) {
            $factorys[$factory] = $factory;
        }
        foreach ($places_array as $place) {
            $places[$place] = $place;
        }

        $users = User::orderBy('order_by')->pluck('name', 'id')->toArray();

        $data = [
            'users' => $users,
            'semester' => $semester,
            'semesters' => $semesters,
            'factorys' => $factorys,
            'places' => $places,
        ];


        return view('lunch.special', $data);
    }

    public function do_special(Request $request)
    {
        switch ($request->input('op')) {
            case "order_tea":
                if (empty(($request->input('user_id')))) {
                    $words = "你沒有選擇老師！";
                    return view('errors.errors', compact('words'));
                }


                $check_order = LunchTeaDate::where('user_id', '=', $request->input('user_id'))->where('order_date', '=', $request->input('b_order_date'))->first();
                if ($check_order) {
                    $words = "這位教職員已經有訂餐記錄！請查明！";
                    return view('errors.errors', compact('words'));
                }

                $order_dates = $this->get_order_dates($request->input('semester'));
                $order_id_array = $this->get_order_id_array($request->input('semester'));
                $b_order_date = str_replace('-', '', $request->input('b_order_date'));
                foreach ($order_dates as $k => $v) {
                    $att['order_date'] = $k;
                    if ($v == 0) {
                        $att['enable'] = "no";
                    } elseif ($v == 1) {
                        $order_date = str_replace('-', '', $k);
                        if ($order_date < $b_order_date) {
                            $att['enable'] = "no_eat";
                        } elseif ($order_date >= $b_order_date) {
                            $att['enable'] = "eat";
                        }
                    }
                    $att['semester'] = $request->input('semester');
                    $att['lunch_order_id'] = $order_id_array[substr($k, 0, 7)];
                    $att['user_id'] = $request->input('user_id');
                    if ($request->input('place') == "班級教室") {
                        $att['place'] = $request->input('classroom');
                    } else {
                        $att['place'] = $request->input('place');
                    }
                    $att['factory'] = $request->input('factory');
                    $att['eat_style'] = $request->input('eat_style');

                    LunchTeaDate::create($att);
                }
                return redirect()->route('lunch.special');

                break;
            case "cancel_tea":
                if (empty(($request->input('user_id')))) {
                    $words = "你沒有選擇老師！";
                    return view('errors.errors', compact('words'));
                }
                $tea_order_data = LunchTeaDate::where('user_id', '=', $request->input('user_id'))->where('order_date', '=', $request->input('c_order_date'))->first();
                if ($tea_order_data) {
                    if ($tea_order_data->enable == "no") {
                        $words = $request->input('c_order_date') . "該日沒有供餐！";
                        return view('errors.errors', compact('words'));
                    }
                    if ($tea_order_data->enable == "no_eat" and $request->input('enable') == "no_eat") {
                        $words = $request->input('c_order_date') . "該師該日早已取消訂餐！";
                        return view('errors.errors', compact('words'));
                    }
                    if ($tea_order_data->enable == "eat" and $request->input('enable') == "eat") {
                        $words = $request->input('c_order_date') . "該師該日早已有訂餐！";
                        return view('errors.errors', compact('words'));
                    }

                    $att['enable'] = $request->input('enable');

                    $tea_order_data->update($att);
                    return redirect()->route('lunch.special');

                } else {
                    $words = "該師無此日的訂餐記錄！";
                    return view('errors.errors', compact('words'));
                }
                break;
            case "change_tea";
                if (empty(($request->input('user_id')))) {
                    $words = "你沒有選擇老師！";
                    return view('errors.errors', compact('words'));
                }

                $tea_order_data = LunchTeaDate::where('user_id', '=', $request->input('user_id'))->where('semester', '=', $request->input('semester'))->first();
                if ($tea_order_data) {
                    if (substr($request->input('change'), 0, 9) == 'eat_style') {
                        $att['eat_style'] = substr($request->input('change'), -1);
                    } else {
                        $att['place'] = $request->input('change');
                    }
                    $g_order_date = str_replace('-', '', $request->input('g_order_date'));
                    $order_dates = $this->get_order_dates($request->input('semester'));
                    foreach ($order_dates as $k => $v) {
                        $order_date = str_replace('-', '', $k);
                        if ($order_date >= $g_order_date) {
                            LunchTeaDate::where('user_id', '=', $request->input('user_id'))->where('order_date', '=', $k)->update($att);
                        }
                    }
                    return redirect()->route('lunch.special');
                } else {
                    $words = "該師無訂餐記錄！";
                    return view('errors.errors', compact('words'));
                }

                break;

            case "change_one_stud";
                $year_class_data = YearClass::where('semester', '=', $request->input('semester'))
                    ->where('year_class', '=', substr($request->input('student_num'), 0, 3))
                    ->first();
                if (empty($year_class_data)) {
                    $words = "查無此班級：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }
                $semester_student = SemesterStudent::where('year_class_id', '=', $year_class_data->id)
                    ->where('num', '=', substr($request->input('student_num'), 3, 2))
                    ->first();
                if (empty($semester_student)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }
                $student = Student::where('id', '=', $semester_student->student_id)->first();
                if (empty($student)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }

                $order_data = LunchStuOrder::where('semester','=',$request->input('semester'))
                    ->where('student_id','=',$student->id)
                    ->first();
                if (empty($order_data)) {
                    $words = "查無此學生的訂餐資料：" . $student->name;
                    return view('errors.errors', compact('words'));
                }
                $att['p_id'] = $request->input('p_id');
                $att['eat_style'] = $request->input('eat_style');
                $order_data->update($att);


                LunchStuDate::where('semester','=',$request->input('semester'))
                ->where('student_id','=',$student->id)
                ->update($att);

                if($request->input('eat_style') ==3 ) {
                    $att1['enable'] = "no_eat";
                }else{
                    $att1['enable'] = "eat";
                }

                LunchStuDate::where('semester','=',$request->input('semester'))
                    ->where('student_id','=',$student->id)
                    ->where('enable','!=','not')
                    ->update($att1);


                return redirect()->route('lunch.special');

            break;
            case "change_stu00";
                $year_class_data = YearClass::where('semester', '=', $request->input('semester'))
                    ->where('year_class', '=', substr($request->input('student_num'), 0, 3))
                    ->first();
                if (empty($year_class_data)) {
                    $words = "查無此班級：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }
                $semester_student = SemesterStudent::where('year_class_id', '=', $year_class_data->id)
                    ->where('num', '=', substr($request->input('student_num'), 3, 2))
                    ->first();
                if (empty($semester_student)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }
                $student = Student::where('id', '=', $semester_student->student_id)->first();
                if (empty($student)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }

                $lunch_order_date = LunchOrderDate::where('order_date','=',$request->input('stu00_order_date'))
                    ->first();
                if (empty($lunch_order_date)) {
                    $words = "此日無訂餐資料：" . $request->input('stu00_order_date');
                    return view('errors.errors', compact('words'));
                }

                if ($lunch_order_date->enable == "0") {
                    $words = "此日不供餐：" . $request->input('stu0_order_date');
                    return view('errors.errors', compact('words'));
                }
                $att['eat_style'] = $request->input('eat_style');

                //之後的訂餐，改葷素
                LunchStuDate::where('student_id','=',$student->id)
                    ->where('order_date', '>=', $request->input('stu00_order_date'))
                    ->update($att);
                return redirect()->route('lunch.special');
            break;
            case "change_stu0";
                $year_class_data = YearClass::where('semester', '=', $request->input('semester'))
                    ->where('year_class', '=', substr($request->input('student_num'), 0, 3))
                    ->first();
                if (empty($year_class_data)) {
                    $words = "查無此班級：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }
                $semester_student = SemesterStudent::where('year_class_id', '=', $year_class_data->id)
                    ->where('num', '=', substr($request->input('student_num'), 3, 2))
                    ->first();
                if (empty($semester_student)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }
                $student = Student::where('id', '=', $semester_student->student_id)->first();
                if (empty($student)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }

                $lunch_order_date = LunchOrderDate::where('order_date','=',$request->input('stu0_order_date'))
                    ->first();
                if (empty($lunch_order_date)) {
                    $words = "此日無訂餐資料：" . $request->input('stu0_order_date');
                    return view('errors.errors', compact('words'));
                }

                if ($lunch_order_date->enable == "0") {
                    $words = "此日不供餐：" . $request->input('stu0_order_date');
                    return view('errors.errors', compact('words'));
                }

                $att['enable'] = $request->input('enable');
                LunchStuDate::where('student_id','=',$student->id)
                    ->where('order_date','=',$request->input('stu0_order_date'))
                    ->update($att);
                return redirect()->route('lunch.special');
            break;

            case "change_stu1";
                if (empty(($request->input('select_class')))) {
                    $words = "你沒有填班級！";
                    return view('errors.errors', compact('words'));
                }
                //取該班該日的資料
                $class_id = $request->input('select_class');
                $order_date = $request->input('stu1_order_date');
                $stu_data = LunchStuDate::where('class_id', '=', $class_id)->where('order_date', '=', $order_date)->where('enable', '=', 'eat');
                $att['enable'] = "abs";
                $stu_data->update($att);
                return redirect()->route('lunch.special');
                break;
            case "change_stu2";
                if (empty(($request->input('select_year')))) {
                    $words = "你沒有填學年！";
                    return view('errors.errors', compact('words'));
                }
                //取該學年該日的資料
                $year_one = $request->input('select_year');
                $order_date = $request->input('stu2_order_date');
                $stu_data = LunchStuDate::where('class_id', 'like', $year_one . '%')->where('order_date', '=', $order_date)->where('enable', '=', 'eat');
                $att['enable'] = "abs";
                $stu_data->update($att);
                return redirect()->route('lunch.special');
                break;
            case "change_stu2-2";
                if (empty(($request->input('select_year')))) {
                    $words = "你沒有填學年！";
                    return view('errors.errors', compact('words'));
                }

                //取該學年該日的資料
                $year_one = $request->input('select_year');
                $order_date = $request->input('stu2-2_order_date');
                $stu_data = LunchStuDate::where('class_id', 'like', $year_one . '%')
                    ->where('eat_style','!=','3')
                    ->where('order_date', '=', $order_date);

                $att['enable'] = "not_in";
                $stu_data->update($att);


                return redirect()->route('lunch.special');
                break;
            case "change_stu3";
                $order_date = $request->input('stu3_order_date');
                $stu_data = LunchStuDate::where('order_date', '=', $order_date)->where('enable', '=', 'eat');
                $att['enable'] = "abs";
                $stu_data->update($att);

                //處理老師
                $tea_order_data = LunchTeaDate::where('order_date', '=', $order_date)->first();
                $att2['enable'] = "no_eat";
                if (!empty($tea_order_data)) {
                    $tea_order_data->update($att2);
                }

                return redirect()->route('lunch.special');
                break;
            case "change_studs";
                $ary_phase = array("\r\n", "\r", " ", "\n", "/");

                $data = nl2br($request->input('studs_data'));
                $data = str_replace($ary_phase, "", $data);

                $studs_data = explode('<br>', $data);


                foreach ($studs_data as $k => $v) {

                    $order_data = explode(',', $v);

                    $student_num = $order_data[0];
                    $order_date = $order_data[1];
                    $att['enable'] = "abs";

                    $class_data = YearClass::where('year_class', '=', substr($student_num, 0, 3))->first();
                    if (empty($class_data)) {
                        $words = "查無此班級：" . $student_num;
                        return view('errors.errors', compact('words'));
                    } else {
                        $year_class_id = $class_data->id;
                    }
                    $student_data = SemesterStudent::where('year_class_id', '=', $year_class_id)->where('num', '=', substr($student_num, 3, 2))->first();
                    if (empty($student_data)) {
                        $words = "查無此學生：" . $student_num;
                        return view('errors.errors', compact('words'));
                    } else {
                        $student_id = $student_data->student_id;
                        echo " 班級座號：" . $student_num . "<br>姓名：" . $student_data->student->name . "<br>請假：" . $order_date . "<br>已完成！<br><br>";

                        LunchStuDate::where('student_id', '=', $student_id)->where('order_date', '=', $order_date)->update($att);

                    }

                }


                return redirect()->route('lunch.special');
                break;
            case "out_stud";
                $year_class_data = YearClass::where('semester', '=', $request->input('semester'))
                    ->where('year_class', '=', substr($request->input('student_num'), 0, 3))
                    ->first();
                if (empty($year_class_data)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }
                $semester_student = SemesterStudent::where('year_class_id', '=', $year_class_data->id)
                    ->where('num', '=', substr($request->input('student_num'), 3, 2))
                    ->first();
                if (empty($semester_student)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                }
                $student = Student::where('id', '=', $semester_student->student_id)->first();
                if (empty($student)) {
                    $words = "查無此學生：" . $request->input('student_num');
                    return view('errors.errors', compact('words'));
                } else {
                    if ($request->input('type') == "out") {
                        $att1['at_school'] = "0";
                        SemesterStudent::where('student_id', '=', $student->id)
                            ->where('semester', '=', $request->input('semester'))
                            ->first()
                            ->update($att1);

                        $att2['enable'] = "out";

                        //全學期的請假，改為轉出
                        LunchStuDate::where('semester', '=', $request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->where('enable', '=', 'abs')
                            ->update($att2);


                        $att4['enable'] = "out";
                        $att4['eat_style'] = "3";
                        //之後的訂餐，改為轉出
                        LunchStuDate::where('semester', '=', $request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->where('enable', '=', 'eat')
                            ->where('order_date', '>=', $request->input('out_stud_order_date'))
                            ->update($att4);

                        //未供餐的順便改一下eat_style
                        $att_not['eat_style']="3";
                        LunchStuDate::where('semester', '=', $request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->where('enable', '=', 'not')
                            ->where('order_date', '>=', $request->input('out_stud_order_date'))
                            ->update($att_not);

                            $att_stu_order1['change_date'] = $request->input('out_stud_order_date');
                            $att_stu_order1['out_in'] = "out";
                            LunchStuOrder::where('semester','=',$request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->update($att_stu_order1);

                    } elseif ($request->input('type') == "no_eat") {
                        $att['enable'] = "abs";
                        LunchStuDate::where('semester', '=', $request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->where('enable', '=', 'eat')
                            ->where('order_date', '>=', $request->input('out_stud_order_date'))
                            ->update($att);

                        $att_stu_order2['change_date'] = $request->input('out_stud_order_date');
                        $att_stu_order2['out_in'] = "no_eat";//不訂了
                        LunchStuOrder::where('semester','=',$request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->update($att_stu_order2);

                    }
                }

                return redirect()->route('lunch.special');
                break;
            case "in_stud";
                if ($request->input('type') == "in") {
                    //填的班級對嗎
                    $year_class_data = YearClass::where('semester', '=', $request->input('semester'))
                        ->where('year_class', '=', substr($request->input('student_num'), 0, 3))
                        ->first();
                    if (empty($year_class_data)) {
                        $words = "查無此班級：" . $request->input('student_num');
                        return view('errors.errors', compact('words'));
                    }
                    //查有學生資料嗎
                    $student = Student::where('sn', '=', $request->input('sn'))
                        ->first();
                    if (!empty($student)) {
                        //有無學期資料了
                        $semester_student = SemesterStudent::where('semester', '=', $request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->first();
                        //若無，就新增學期資料
                        if (empty($semester_student)) {
                            $att3['semester'] = $request->input('semester');
                            $att3['student_id'] = $student->id;
                            $att3['year_class_id'] = $year_class_data->id;
                            $att3['num'] = substr($request->input('student_num'), 3, 2);
                            $att3['at_school'] = "1";
                            SemesterStudent::create($att3);
                        } else {
                            if ($semester_student->year_class->year_class . $semester_student->num <> $request->input('student_num')) {
                                $words = "此學生班級座號和你填的不一致：你填的：" . $request->input('student_num') . "但系統內為：" . $semester_student->year_class->year_class . $semester_student->num;
                                return view('errors.errors', compact('words'));
                            }

                        }

                        $student_id = $student->id;

                    } else {
                        //沒學生資料，就增加
                        $att['sn'] = $request->input('sn');
                        $att['name'] = $request->input('name');
                        $att['sex'] = $request->input('sex');
                        //新增學生
                        $add_student = Student::create($att);

                        //新增此學期資料
                        $att2['semester'] = $request->input('semester');
                        $att2['student_id'] = $add_student->id;
                        $att2['year_class_id'] = $year_class_data->id;
                        $att2['num'] = substr($request->input('student_num'), 3, 2);
                        $att2['at_school'] = "1";
                        SemesterStudent::create($att2);
                        $student_id = $add_student->id;

                    }

                    //新增訂餐資料
                    $semester = $request->input('semester');
                    $order_dates = $this->get_order_dates($semester);
                    $order_id_array = $this->get_order_id_array($semester);

                    $att5['semester'] = $semester;
                    $att5['student_id'] = $student_id;
                    $att5['student_num'] = $request->input('student_num');
                    $att5['eat_style'] = $request->input('eat_style');
                    $att5['p_id'] = $request->input('p_id');
                    $att5['out_in'] = "in";
                    $att5['change_date'] = $request->input('in_stud_order_date');
                    LunchStuOrder::create($att5);

                    //日期訂餐
                    foreach ($order_dates as $k => $v) {
                        $att4['order_date'] = $k;
                        $att4['semester'] = $semester;
                        $att4['lunch_order_id'] = $order_id_array[substr($k, 0, 7)];
                        $att4['student_id'] = $student_id;
                        $att4['class_id'] = substr($request->input('student_num'), 0, 3);
                        $att4['num'] = substr($request->input('student_num'), 3, 2);
                        $att4['p_id'] = $request->input('p_id');
                        $att4['eat_style'] = $request->input('eat_style');
                        if (str_replace('-', '', $k) < str_replace('-', '', $request->input('in_stud_order_date'))) {
                            if ($v == "0") $att4['enable'] = "not";
                            if ($v == "1") $att4['enable'] = "no_eat";
                            $att4['eat_style'] = "3";
                            $att4['p_id'] = "301";//轉入前，身份為轉入生
                        } else {
                            if ($v == "0") $att4['enable'] = "not";
                            if ($v == "1") $att4['enable'] = "eat";
                            if ($att4['eat_style'] == "3" and $v == "1") $att4['enable'] = "no_eat";
                        }

                        LunchStuDate::create($att4);
                    }


                } elseif ($request->input('type') == "eat") {
                    $year_class_data = YearClass::where('semester', '=', $request->input('semester'))
                        ->where('year_class', '=', substr($request->input('student_num'), 0, 3))
                        ->first();
                    if (empty($year_class_data)) {
                        $words = "查無此學生：" . $request->input('student_num');
                        return view('errors.errors', compact('words'));
                    }
                    $semester_student = SemesterStudent::where('year_class_id', '=', $year_class_data->id)
                        ->where('num', '=', substr($request->input('student_num'), 3, 2))
                        ->first();
                    if (empty($semester_student)) {
                        $words = "查無此學生：" . $request->input('student_num');
                        return view('errors.errors', compact('words'));
                    }
                    $student = Student::where('id', '=', $semester_student->student_id)->first();
                    if (empty($student)) {
                        $words = "查無此學生：" . $request->input('student_num');
                        return view('errors.errors', compact('words'));
                    } else {
                        $att['enable'] = "eat";
                        $att['eat_style'] = $request->input('eat_style');
                        $att['p_id'] = $request->input('p_id');
                        //更改訂餐
                        LunchStuDate::where('semester', '=', $request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->where('enable', '=', 'no_eat')
                            ->where('order_date', '>=', $request->input('in_stud_order_date'))
                            ->update($att);
                        //把未供餐的順便改一下eat_style,p_id
                        $att['enable'] = "not";
                        LunchStuDate::where('semester', '=', $request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->where('enable', '=', 'not')
                            ->where('order_date', '>=', $request->input('in_stud_order_date'))
                            ->update($att);

                        //更改order記錄
                        $att_stu_order['change_date'] = $request->input('in_stud_order_date');
                        $att_stu_order['out_in'] = "eat";//又訂了
                        LunchStuOrder::where('semester','=',$request->input('semester'))
                            ->where('student_id', '=', $student->id)
                            ->update($att_stu_order);

                    }
                }
                return redirect()->route('lunch.special');
                break;

        }
    }


    public function report(Request $request)
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');

        //查目前學期
        $y = date('Y') - 1911;
        $array1 = array(8, 9, 10, 11, 12, 1);
        $array2 = array(2, 3, 4, 5, 6, 7);
        if (in_array(date('n'), $array1)) {
            if (date('n') == 1) {
                $this_semester = ($y - 1) . "1";
            } else {
                $this_semester = $y . "1";
            }
        } else {
            $this_semester = ($y - 1) . "2";
        }

        $semester = (empty($request->input('semester'))) ? $this_semester : $request->input('semester');

        $semesters = LunchSetup::all()->pluck('semester', 'semester')->toArray();

        $data = [
            'semester' => $semester,
            'semesters' => $semesters
        ];

        return view('lunch.report', $data);
    }

    public function report_tea1(Request $request)
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');

        $orders = $this->get_order_id_array($request->input('semester'));
        $this_mon = date('Y-m');
        $this_order_id = $orders[$this_mon];
        //選取的月份id
        $order_id = (empty($request->input('order_id'))) ? $this_order_id : $request->input('order_id');

        $orders = array_flip($orders);
        //選取的月份
        $mon = $orders[$order_id];

        $o_order_dates = $this->get_order_dates($request->input('semester'));
        $i = 0;
        //訂餐日期array
        foreach ($o_order_dates as $k => $v) {
            if (substr($k, 0, 7) == $mon and $v == 1) {
                $order_dates[$i] = $k;
                $i++;
            }
        }
        //訂餐者資料
        $user_datas = [];
        $order_datas = LunchTeaDate::where('lunch_order_id', '=', $order_id)
            ->orderBy('place','ASC')
            ->orderBy('user_id')
            ->get();
        foreach ($order_datas as $order_data) {
            $user_datas[$order_data->user->name][$order_data->order_date]['enable'] = $order_data->enable;
            $user_datas[$order_data->user->name][$order_data->order_date]['eat_style'] = $order_data->eat_style;
            $user_datas[$order_data->user->name][$order_data->order_date]['place'] = $order_data->place;
        }

        $data = [
            'this_order_id' => $this_order_id,
            'mon' => $mon,
            'orders' => $orders,
            'semester' => $request->input('semester'),
            'order_dates' => $order_dates,
            'user_datas' => $user_datas,
        ];
        return view('lunch.report_tea1', $data);
    }

    public function report_tea2(Request $request)
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');

        $order_datas = LunchTeaDate::where('semester', '=', $request->input('semester'))
            ->orderBy('lunch_order_id')
            ->orderBy('place','ASC')
            ->orderBy('user_id')
            ->get();

        foreach ($order_datas as $order_data) {
            if ($order_data->enable == "eat") {
                if( !isset($user_datas[$order_data->user->name])) $user_datas[$order_data->user->name]=null;
                $user_datas[$order_data->user->name]++;
            }
        }

        if(!isset($user_datas)) $user_datas = [];

        $setups = $this->get_setup();

        $data = [
            'semester' => $request->input('semester'),
            'user_datas' => $user_datas,
            'tea_money' => $setups[$request->input('semester')]['tea_money'],
        ];
        return view('lunch.report_tea2', $data);
    }

    public function report_tea2_print(Request $request)
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');
        $order_datas = LunchTeaDate::where('semester', '=', $request->input('semester'))
            ->orderBy('lunch_order_id')
            ->orderBy('place','ASC')
            ->orderBy('user_id')
            ->get();


        foreach ($order_datas as $order_data) {
            if ($order_data->enable == "eat") {
                if(!isset($user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)])) $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]=null;
                $user_datas[$order_data->user->name][substr($order_data->order_date, 0, 7)]++;
            }
        }

        if(!isset($user_datas)) $user_datas = [];

        $setups = $this->get_setup();
        $data = [
            'semester'=>$request->input('semester'),
            'user_datas' => $user_datas,
            'tea_money' => $setups[$request->input('semester')]['tea_money'],
        ];
        return view('lunch.report_tea2_print', $data);

    }

    public function report_stu1(Request $request)
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');
        $semester = $request->input('semester');

        $order_dates = $this->get_order_dates($semester);
        foreach ($order_dates as $k => $v) {
            if ($v == 1) $select_date_menu[$k] = $k;
        }
        $select_date = (empty($request->input('select_date'))) ? current($select_date_menu) : $request->input('select_date');


        $class_orders_dates = LunchStuDate::where('semester', '=', $request->input('semester'))->orderBy('class_id');


        $class_orders = $class_orders_dates->where('order_date', '=', $select_date)->get();

        $last_class = "";
        $order_data=[];

        foreach ($class_orders as $class_order) {

            $class_id = $class_order->class_id;
            $order_date = $class_order->order_date;
            $eat_style = $class_order->eat_style;
            $p_id = $class_order->p_id;
            $sex = $class_order->student->sex;

            if ($class_id != $last_class) {
                $g = 0;
                $w = 0;
                $n = 0;
                $a = 0;
                $ab = 0;
                $ag = 0;
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
                $w201b = 0;
                $w201g = 0;
                $w202b = 0;
                $w202g = 0;
                $w203b = 0;
                $w203g = 0;
                $w204b = 0;
                $w204g = 0;
                $w205b = 0;
                $w205g = 0;
                $w206b = 0;
                $w206g = 0;
                $w207b = 0;
                $w207g = 0;
                $w208b = 0;
                $w208g = 0;
                $w209b = 0;
                $w209g = 0;
                $w210b = 0;
                $w210g = 0;
                $w301b = 0;
                $w301g = 0;
            }



            if ($p_id > 200 and $p_id < 300 and $eat_style != 3) {
                $w++;
                $order_data[$class_id][$order_date]['w'] = $w;
                if ($sex == 1) {
                    $wb++;
                    $order_data[$class_id][$order_date]['wb'] = $wb;
                } else {
                    $wg++;
                    $order_data[$class_id][$order_date]['wg'] = $wg;
                }
                if ($p_id == 201) {
                    $w201++;
                    $order_data[$class_id][$order_date]['w201'] = $w201;
                    if ($sex == 1) {
                        $w201b++;
                        $order_data[$class_id][$order_date]['w201b'] = $w201b;
                    } else {
                        $w201g++;
                        $order_data[$class_id][$order_date]['w201g'] = $wg;
                    }
                } elseif ($p_id == 202) {
                    $w202++;
                    $order_data[$class_id][$order_date]['w202'] = $w202;
                    if ($sex == 1) {
                        $w202b++;
                        $order_data[$class_id][$order_date]['w202b'] = $w202b;
                    } else {
                        $w202g++;
                        $order_data[$class_id][$order_date]['w202g'] = $w202g;
                    }
                } elseif ($p_id == 203) {
                    $w203++;
                    $order_data[$class_id][$order_date]['w203'] = $w203;
                    if ($sex == 1) {
                        $w203b++;
                        $order_data[$class_id][$order_date]['w203b'] = $w203b;
                    } else {
                        $w203g++;
                        $order_data[$class_id][$order_date]['w203g'] = $w203g;
                    }
                } elseif ($p_id == 204) {
                    $w204++;
                    $order_data[$class_id][$order_date]['w204'] = $w204;
                    if ($sex == 1) {
                        $w204b++;
                        $order_data[$class_id][$order_date]['w204b'] = $w204b;
                    } else {
                        $w204g++;
                        $order_data[$class_id][$order_date]['w204g'] = $w204g;
                    }
                } elseif ($p_id == 205) {
                    $w205++;
                    $order_data[$class_id][$order_date]['w205'] = $w205;
                    if ($sex == 1) {
                        $w205b++;
                        $order_data[$class_id][$order_date]['w205b'] = $w205b;
                    } else {
                        $w205g++;
                        $order_data[$class_id][$order_date]['w205g'] = $w205g;
                    }
                } elseif ($p_id == 206) {
                    $w206++;
                    $order_data[$class_id][$order_date]['w206'] = $w206;
                    if ($sex == 1) {
                        $w206b++;
                        $order_data[$class_id][$order_date]['w206b'] = $w206b;
                    } else {
                        $w206g++;
                        $order_data[$class_id][$order_date]['w206g'] = $w206g;
                    }
                } elseif ($p_id == 207) {
                    $w207++;
                    $order_data[$class_id][$order_date]['w207'] = $w207;
                    if ($sex == 1) {
                        $w207b++;
                        $order_data[$class_id][$order_date]['w207b'] = $w207b;
                    } else {
                        $w207g++;
                        $order_data[$class_id][$order_date]['w207g'] = $w207g;
                    }
                } elseif ($p_id == 208) {
                    $w208++;
                    $order_data[$class_id][$order_date]['w208'] = $w208;
                    if ($sex == 1) {
                        $w208b++;
                        $order_data[$class_id][$order_date]['w208b'] = $w208b;
                    } else {
                        $w208g++;
                        $order_data[$class_id][$order_date]['w208g'] = $w208g;
                    }
                } elseif ($p_id == 209) {
                    $w209++;
                    $order_data[$class_id][$order_date]['w209'] = $w209;
                    if ($sex == 1) {
                        $w209b++;
                        $order_data[$class_id][$order_date]['w209b'] = $w209b;
                    } else {
                        $w209g++;
                        $order_data[$class_id][$order_date]['w209g'] = $w209g;
                    }
                } elseif ($p_id == 210) {
                    $w210++;
                    $order_data[$class_id][$order_date]['w210'] = $w210;
                    if ($sex == 1) {
                        $w210b++;
                        $order_data[$class_id][$order_date]['w210b'] = $w210b;
                    } else {
                        $w210g++;
                        $order_data[$class_id][$order_date]['w210g'] = $w210g;
                    }
                }
            }elseif($p_id == 301 and $eat_style != 3){
                $a++;
                $order_data[$class_id][$order_date]['a'] = $a;
                if ($sex == 1) {
                    $ab++;
                    $order_data[$class_id][$order_date]['ab'] = $ab;
                } else {
                    $ag++;
                    $order_data[$class_id][$order_date]['ag'] = $ag;
                }
            } elseif ($p_id == 101 and $eat_style != 3) {
                $g++;
                $order_data[$class_id][$order_date]['g'] = $g;
                if ($sex == 1) {
                    $gb++;
                    $order_data[$class_id][$order_date]['gb'] = $gb;
                } else {
                    $gg++;
                    $order_data[$class_id][$order_date]['gg'] = $gg;
                }
            } elseif ($eat_style == 3) {
                $n++;
                $order_data[$class_id][$order_date]['n'] = $n;
                if ($sex == 1) {
                    $nb++;
                    $order_data[$class_id][$order_date]['nb'] = $nb;
                } else {
                    $ng++;
                    $order_data[$class_id][$order_date]['ng'] = $ng;
                }
            }

            $last_class = $class_id;

        }

        $setups = $this->get_setup();
        if($setups[$semester]['stud_money'] == '0'){
            $all_support = "1";
        }else{
            $all_support = "0";//全校都補助
        }


        $data = [
            'semester' => $semester,
            'select_date' => $select_date,
            'select_date_menu' => $select_date_menu,
            'order_data' => $order_data,
            'all_support'=>$all_support,
        ];
        return view('lunch.report_stu1', $data);

    }

    public function report_stu2(Request $request)
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');
        $semester = $request->input('semester');
        $order_id_array = $this->get_order_id_array($semester);
        $lunch_orders = array_flip($order_id_array);
        $lunch_order_id = (empty($request->input('select_order_id'))) ? $order_id_array[substr(date('Y-m'), 0, 7)] : $request->input('select_order_id');


        $order_dates = $this->get_order_dates($semester);
        $i = 0;
        foreach ($order_dates as $k => $v) {
            if ($v == 1 and substr($k, 0, 7) == $lunch_orders[$lunch_order_id]) {
                $this_order_dates[$i] = $k;
                $i++;
            }
        }
        $order_data=array();
        $stu_order_datas = LunchStuDate::where('lunch_order_id', '=', $lunch_order_id)
            ->where('enable','=','eat')
            ->orderBy('class_id')->orderBy('order_date')->get();

        foreach ($stu_order_datas as $stu_order_data) {
            if ($stu_order_data->p_id > 200 and $stu_order_data->p_id < 300 and $stu_order_data->eat_style != 3 and $stu_order_data->enable == "eat") {
                if (!isset($order_data[$stu_order_data->class_id][$stu_order_data->order_date]['w'])) {
                    $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['w'] = null;
                }
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['w']++;
            }elseif($stu_order_data->p_id == 301 and $stu_order_data->eat_style != 3 and $stu_order_data->enable == "eat"){
                if ( ! isset($order_data[$stu_order_data->class_id][$stu_order_data->order_date]['a'])) {
                    $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['a'] = null;
                }
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['a']++;
            } elseif ($stu_order_data->p_id == 101 and $stu_order_data->eat_style != 3 and $stu_order_data->enable == "eat") {
                if ( ! isset($order_data[$stu_order_data->class_id][$stu_order_data->order_date]['g'])) {
                    $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['g'] = null;
                }
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['g']++;
            }
        }

        $setups = $this->get_setup();
        if($setups[$semester]['stud_money'] == '0'){
            $all_support = "1";
        }else{
            $all_support = "0";//全校都補助
        }


        $data = [
            'semester' => $semester,
            'lunch_orders' => $lunch_orders,
            'lunch_order_id' => $lunch_order_id,
            'this_order_dates' => $this_order_dates,
            'order_data' => $order_data,
            'all_support'=> $all_support,
        ];
        return view('lunch.report_stu2', $data);
    }

    public function report_stu3(Request $request){
        //參數
        $semester = $request->input('semester');

        $setup = $this->get_setup();

        $stu_abs_data = LunchStuDate::where('semester','=',$semester)
            ->where('p_id','<','200')
            ->orderBy('class_id')
            ->orderBy('num')
            ->orderBy('order_date')
            ->get();
        $abs_data = [];
        $out_data = [];
        foreach($stu_abs_data as $stu_abs){
            if($stu_abs->enable == "abs") {
                $abs_data[$stu_abs->class_id . $stu_abs->num]['name'] = $stu_abs->student->name;
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'] += $setup[$semester]['stud_back_money'];
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['times'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['times'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['times']++;
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['dates'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['dates'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['dates'] .= $stu_abs->order_date . ",";
            }elseif($stu_abs->enable == "out"){
                $out_data[$stu_abs->class_id . $stu_abs->num]['name'] = $stu_abs->student->name;
                if (!isset($out_data[$stu_abs->class_id . $stu_abs->num]['back_money'])) {
                    $out_data[$stu_abs->class_id . $stu_abs->num]['back_money'] = null;
                }
                $out_data[$stu_abs->class_id . $stu_abs->num]['back_money'] += $setup[$semester]['stud_back_money'];
                if (!isset($out_data[$stu_abs->class_id . $stu_abs->num]['times'])) {
                    $out_data[$stu_abs->class_id . $stu_abs->num]['times'] = null;
                }
                $out_data[$stu_abs->class_id . $stu_abs->num]['times']++;
                if (!isset($out_data[$stu_abs->class_id . $stu_abs->num]['dates'])) {
                    $out_data[$stu_abs->class_id . $stu_abs->num]['dates'] = null;
                }
                $out_data[$stu_abs->class_id . $stu_abs->num]['dates'] .= $stu_abs->order_date . ",";
            }
        }
        //依班級座號排序
        ksort($out_data);
        ksort($abs_data);

        $data =[
            'semester' => $semester,
            'abs_data'=>$abs_data,
            'out_data'=>$out_data,
        ];
        return view('lunch.report_stu3', $data);
    }

    public function report_stu3_print(Request $request){
        //參數
        $semester = $request->input('semester');

        $setup = $this->get_setup();

        $stu_abs_data = LunchStuDate::where('semester','=',$semester)
            ->where('p_id','<','200')
            ->orderBy('class_id')
            ->get();
        $abs_data = [];
        foreach($stu_abs_data as $stu_abs){
            if($stu_abs->enable == "abs") {
                $abs_data[$stu_abs->class_id . $stu_abs->num]['name'] = $stu_abs->student->name;
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'] += $setup[$semester]['stud_back_money'];
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['times'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['times'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['times']++;
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['dates'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['dates'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['dates'] .= $stu_abs->order_date . ",";
            }
        }
        //依班級座號排序
        ksort($abs_data);

        $data =[
            'semester' => $semester,
            'abs_data'=>$abs_data,
        ];
        return view('lunch.report_stu3_print', $data);
    }


    public function report_cashier1(Request $request)
    {
        $semester = $request->input('semester');
        $data =[
            'semester' => $semester,
        ];
        return view('lunch.report_cashier1',$data);
    }

    public function download_cashier_demo()
    {
        $realFile = asset('cashier_demo.csv');
        header("Content-type:application");
        //header("Content-Length: " .(string)(filesize($realFile)));
        header("Content-Disposition: attachment; filename=學生帳戶資料.csv");
        readfile($realFile);
    }

    public function export_cashier(Request $request)
    {
        if(Input::hasFile('csv')) {

            $filePath = $request->file('csv')->getRealPath();

            $data = Excel::load($filePath, function ($reader) {
            })->get();

            foreach ($data as $k => $v) {
                $import_data[$v['學號']]['轉帳戶名'] = $v['轉帳戶名'];
                $import_data[$v['學號']]['轉帳戶身份證編號'] = $v['轉帳戶身份證編號'];
                $import_data[$v['學號']]['立帳局號'] = $v['立帳局號'];
                $import_data[$v['學號']]['存簿帳號'] = $v['存簿帳號'];
            }


            //退費的資料
            $semester = $request->input('semester');

            $setup = $this->get_setup();

            $stu_abs_data = LunchStuDate::where('semester','=',$semester)
                ->where('p_id','<','200')
                ->where('enable','=','abs')
                ->orderBy('class_id')
                ->get();
            $abs_data = [];
            foreach($stu_abs_data as $stu_abs){
                $abs_data[$stu_abs->class_id . $stu_abs->num]['name'] = $stu_abs->student->name;
                $abs_data[$stu_abs->class_id . $stu_abs->num]['year'] = substr($stu_abs->class_id,0,1);
                $abs_data[$stu_abs->class_id . $stu_abs->num]['class'] = (int)substr($stu_abs->class_id,1,2);
                $abs_data[$stu_abs->class_id . $stu_abs->num]['num'] = (int)$stu_abs->num;
                $abs_data[$stu_abs->class_id . $stu_abs->num]['sn'] = $stu_abs->student->sn;
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'] += $setup[$semester]['stud_back_money'];
            }
            //依班級座號排序
            ksort($abs_data);

            $final_data = "年級,班級代號,座號,學號,學生姓名,轉帳戶名,轉帳戶身份證編號,立帳局號,存簿帳號,退費金額\r\n";
            foreach($abs_data as $k => $v){
                if(!isset($import_data[$v['sn']]['轉帳戶名']) or !isset($import_data[$v['sn']]['轉帳戶身份證編號']) or !isset($import_data[$v['sn']]['立帳局號']) or !isset($import_data[$v['sn']]['存簿帳號'])){
                    $import_data[$v['sn']]['轉帳戶名'] = null;
                    $import_data[$v['sn']]['轉帳戶身份證編號'] = null;
                    $import_data[$v['sn']]['立帳局號'] = null;
                    $import_data[$v['sn']]['存簿帳號'] = null;
                }
                $final_data .= $v['year'].",".$v['class'].",".$v['num'].",".$v['sn'].",".$v['name'].",".$import_data[$v['sn']]['轉帳戶名'].",".$import_data[$v['sn']]['轉帳戶身份證編號'].",".$import_data[$v['sn']]['立帳局號'].",".$import_data[$v['sn']]['存簿帳號'].",".$v['back_money']."\r\n";
            }

            header("Content-type:application");
            header("Content-Disposition: attachment; filename=給出納組-學生退費資料.csv");
            echo $final_data;
        }

    }
    public function report_master1(Request $request){
        //參數
        $semester = $request->input('semester');

        $setup = $this->get_setup();

        $stu_abs_data = LunchStuDate::where('semester','=',$semester)
            ->where('p_id','<','200')
            ->orderBy('class_id')
            ->get();
        $abs_data = [];
        $total_money = 0;
        $total_times = 0;
        foreach($stu_abs_data as $stu_abs){
            if($stu_abs->enable == "abs") {
                $abs_data[$stu_abs->class_id . $stu_abs->num]['name'] = $stu_abs->student->name;
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['back_money'] += $setup[$semester]['stud_back_money'];
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['times'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['times'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['times']++;
                if (!isset($abs_data[$stu_abs->class_id . $stu_abs->num]['dates'])) {
                    $abs_data[$stu_abs->class_id . $stu_abs->num]['dates'] = null;
                }
                $abs_data[$stu_abs->class_id . $stu_abs->num]['dates'] .= $stu_abs->order_date . ",";
                $total_money += $setup[$semester]['stud_back_money'];
                $total_times++;
            }
        }
        //依班級座號排序
        ksort($abs_data);

        $data =[
            'semester' => $semester,
            'abs_data'=>$abs_data,
            'total_money'=>$total_money,
            'total_times'=>$total_times,
        ];
        return view('lunch.report_master1_print', $data);
    }

    public function report_master2(Request $request)
    {
        $orders = $this->get_order_id_array($request->input('semester'));
        $this_mon = date('Y-m');
        $this_order_id = $orders[$this_mon];
        //選取的月份id
        $order_id = (empty($request->input('order_id'))) ? $this_order_id : $request->input('order_id');

        $semester = $request->input('semester');

        $orders = array_flip($orders);

        if(substr($semester,3,1)=="1"){
            $orders['前2月']='8~9月';
            ksort($orders);
        }else{
            $orders['前2月']='2~3月';
            ksort($orders);
        }


        if($order_id == "前2月") {
            $k1 = key($orders);
            next($orders);
            $k2 = key($orders);
            next($orders);
            $k3 = key($orders);
            $mon2 = [$k2, $k3];
            if (substr($semester, 3, 1) == "1") {
                $mon = '8~9月';
            } else {
                $mon = '2~3月';
            }
        }else{
            //選取的月份
            $mon = $orders[$order_id];
        }



        $setup = $this->get_setup();
        $stud_money = $setup[$semester]['stud_money'];
        $stud_back_money = $setup[$semester]['stud_back_money'];
        $support_part_money = $setup[$semester]['support_part_money'];
        $support_all_money = $setup[$semester]['support_all_money'];


        if($order_id == "前2月") {
            $stu_orders = LunchStuDate::whereIn('lunch_order_id', $mon2)
                ->where('enable','=','eat')
                ->get();
        }else{
            $stu_orders = LunchStuDate::where('semester','=',$semester)
                ->where('order_date','like',$mon.'%')
                ->where('enable','=','eat')
                ->get();
        }

        $total_g = 0;
        $total_w = 0;
        $total_a = 0;
        $class_data =[];
        foreach($stu_orders as $stu_order){
            if(!isset($class_data[$stu_order->class_id]['g'])) $class_data[$stu_order->class_id]['g']=0;
            if(!isset($class_data[$stu_order->class_id]['w'])) $class_data[$stu_order->class_id]['w']=0;
            if(!isset($class_data[$stu_order->class_id]['a'])) $class_data[$stu_order->class_id]['a']=0;
            if($stu_order->p_id == "101"){
                $class_data[$stu_order->class_id]['g']++;
                $total_g++;
            }elseif($stu_order->p_id > 200 and $stu_order->p_id < 300){
                $class_data[$stu_order->class_id]['w']++;
                $total_w++;
            }elseif($stu_order->p_id == "301"){
                $class_data[$stu_order->class_id]['a']++;
                $total_a++;
            }
        }
        if(!empty($class_data)) ksort($class_data);

        //算各月收入多少錢
        if($order_id == "前2月") {
            $mon_eat_days[$mon] = LunchOrderDate::whereIn('lunch_order_id',$mon2)
                ->where('enable','=','1')->count();
        }

        $order_dates = $this->get_order_dates($semester);
        foreach ($order_dates as $k => $v) {
            if ($v == "1") {
                if (!isset($mon_eat_days[substr($k, 0, 7)])) $mon_eat_days[substr($k, 0, 7)] = null;
                $mon_eat_days[substr($k, 0, 7)]++;
            }
        }


        //本學期第一天的日期，自費的人數
        $first_day = current(array_keys($order_dates));
        $total_stu_order_num = LunchStuDate::where('order_date','=',$first_day)
            ->where('eat_style','!=','3')
            ->where('p_id','<','200')
            ->count();

        //請假的一般生人數
        foreach($orders as $k =>$v) {
            if($v == "前2月"){
                $abs_num[$mon] = LunchStuDate::whereIn('lunch_order_id', '=', $mon2)
                    ->where('enable', '=', 'abs')
                    ->where('p_id', '<', '200')
                    ->count();

                $eat_num[$mon] = LunchStuDate::whereIn('lunch_order_id', '=', $mon2)
                    ->where('enable', '=', 'eat')
                    ->where('p_id', '<', '200')
                    ->count();

                $out_num[$mon] = LunchStuDate::whereIn('lunch_order_id', '=', $mon2)
                    ->where('enable', '=', 'out')
                    ->where('p_id', '<', '200')
                    ->count();

                //算出納當初就沒算錢的人次
                $not_in_num[$mon] = LunchStuDate::whereIn('lunch_order_id', '=', $mon2)
                    ->where('enable', '=', 'not_in')
                    ->where('p_id', '<', '200')
                    ->count();
            }else{
                $abs_num[$v] = LunchStuDate::where('semester', '=', $semester)
                    ->where('order_date', 'like', $v . '%')
                    ->where('enable', '=', 'abs')
                    ->where('p_id', '<', '200')
                    ->count();

                $eat_num[$v] = LunchStuDate::where('semester', '=', $semester)
                    ->where('order_date', 'like', $v . '%')
                    ->where('enable', '=', 'eat')
                    ->where('p_id', '<', '200')
                    ->count();

                $out_num[$v] = LunchStuDate::where('semester', '=', $semester)
                    ->where('order_date', 'like', $v . '%')
                    ->where('enable', '=', 'out')
                    ->where('p_id', '<', '200')
                    ->count();

                //算出納當初就沒算錢的人次
                $not_in_num[$v] = LunchStuDate::where('semester', '=', $semester)
                    ->where('order_date', 'like', $v . '%')
                    ->where('enable', '=', 'not_in')
                    ->where('p_id', '<', '200')
                    ->count();
            }

        }

        //轉入生或臨時又要訂餐的補交錢
        $in_eat_data = LunchStuOrder::where('semester','=',$semester)
            ->where('out_in','!=','')
            ->where('p_id', '<', '200')
            ->get();




        foreach($orders as $k=>$v){
            $in_num[$v] = 0;
        }

        foreach($in_eat_data as $a){
            if($a->out_in =="in" or $a->out_in =="eat"){
                $c = LunchStuDate::where('semester','=',$semester)
                    ->where('student_id','=',$a->student_id)
                    ->where('p_id', '<', '200')
                    ->where('enable','!=','not')
                    ->get();
                foreach($c as $d){
                    if(!isset($in_num[substr($d->order_date,0,7)])) $in_num[substr($d->order_date,0,7)]=0;
                    $in_num[substr($d->order_date,0,7)]++;
                }

            }
        }
        //前二月的總計
        if($order_id == "前2月") {
            foreach ($mon2 as $k => $v) {
                if(!isset($in_num[$mon])) $in_num[$mon]=null;
                $in_num[$mon] += $in_num[$orders[$v]];
            }
        }

        if($setup[$semester]['stud_money'] == '0'){
            $data = [
                'semester' => $semester,
                'mon' => $mon,
                'orders' => $orders,
                'this_order_id' => $this_order_id,
                'class_data' => $class_data,
                'support_all_money' => $support_all_money,
                'total_a' => $total_a,
            ];
            return view('lunch.report_master2-a',$data);
        }else{
            $data =[
                'semester' => $semester,
                'mon' => $mon,
                'orders' => $orders,
                'this_order_id' => $this_order_id,
                'total_g' => $total_g,
                'total_w' => $total_w,
                'stud_money' => $stud_money,
                'stud_back_money' => $stud_back_money,
                'support_part_money' => $support_part_money,
                'support_all_money' => $support_all_money,
                'class_data' => $class_data,
                'mon_eat_days' =>$mon_eat_days,
                'total_stu_order_num'=>$total_stu_order_num,
                'abs_num'=>$abs_num,
                'eat_num'=>$eat_num,
                'out_num'=>$out_num,
                'in_num'=>$in_num,
                'not_in_num'=>$not_in_num,
            ];

            return view('lunch.report_master2',$data);
        }


    }

    public function report_master4(Request $request)
    {
        $orders = $this->get_order_id_array($request->input('semester'));
        $this_mon = date('Y-m');
        $this_order_id = $orders[$this_mon];
        //選取的月份id
        $order_id = (empty($request->input('order_id'))) ? $this_order_id : $request->input('order_id');

        $orders = array_flip($orders);



        $semester = $request->input('semester');

        if(substr($semester,3,1)=="1"){
            $orders['前2月']='8~9月';
            ksort($orders);
        }else{
            $orders['前2月']='2~3月';
            ksort($orders);
        }

        $setup = $this->get_setup();

        $tea_money = $setup[$semester]['tea_money'];

        if($order_id == "前2月"){
            $k1 = key($orders);
            next($orders);
            $k2 = key($orders);
            next($orders);
            $k3 = key($orders);
            $mon2 = [$k2,$k3];
            if(substr($semester,3,1)=="1"){
                $mon='8~9月';
            }else{
                $mon='2~3月';
            }
            $num = LunchTeaDate::whereIn('lunch_order_id', $mon2)
                ->where('enable', '=', 'eat')
                ->count();
        }else {
            //選取的月份
            $mon = $orders[$order_id];

            $num = LunchTeaDate::where('lunch_order_id', '=', $order_id)
                ->where('enable', '=', 'eat')
                ->count();
        }

        //取名字
        $users = User::where('unactive','=',null)->get();
        foreach($users as $user){
            $tea[$user->id] = $user->name;
        }

        //取老師訂餐
        if($order_id == "前2月") {
            $get_tea_data = LunchTeaDate::whereIn('lunch_order_id', $mon2)
                ->where('enable', '=', 'eat')
                ->orderBy('place', 'ASC')
                ->orderBy('user_id')
                ->get();
        }else {
            $get_tea_data = LunchTeaDate::where('lunch_order_id', '=', $order_id)
                ->where('enable', '=', 'eat')
                ->orderBy('place', 'ASC')
                ->orderBy('user_id')
                ->get();
        }

        $tea_order = [];

        foreach($get_tea_data as $tea_data){
            if(!isset($tea_order[$tea[$tea_data->user_id]])) $tea_order[$tea[$tea_data->user_id]]=null;
            $tea_order[$tea[$tea_data->user_id]]++;
        }


        $data =[
            'semester' => $semester,
            'mon' => $mon,
            'orders' => $orders,
            'this_order_id' => $this_order_id,
            'tea_money'=>$tea_money,
            'num'=>$num,
            'tea_order'=>$tea_order,
        ];

        return view('lunch.report_master4',$data);
    }

    public function report_master3(Request $request)
    {
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if (empty($check)) return view('errors.not_admin');
        $semester = $request->input('semester');
        $order_id_array = $this->get_order_id_array($semester);
        $lunch_orders = array_flip($order_id_array);
        $lunch_order_id = (empty($request->input('select_order_id'))) ? $order_id_array[substr(date('Y-m'), 0, 7)] : $request->input('select_order_id');

        if(substr($semester,3,1)=="1"){
            $lunch_orders['前2月']='8~9月';
            ksort($lunch_orders);
        }else{
            $lunch_orders['前2月']='2~3月';
            ksort($lunch_orders);
        }

        if($lunch_order_id == "前2月"){
            $k1 = key($lunch_orders);
            next($lunch_orders);
            $k2 = key($lunch_orders);
            next($lunch_orders);
            $k3 = key($lunch_orders);
            $mon2 = [$k2,$k3];


            $dates = LunchOrderDate::whereIn('lunch_order_id',$mon2)
                ->where('enable','=','1')
                ->orderBy('order_date')
                ->get();
            $i = 0;
            foreach($dates as $date){
                $this_order_dates[$i] = $date->order_date;
                $i++;
            }

            $order_data=array();
            $stu_order_datas = LunchStuDate::whereIn('lunch_order_id',$mon2)
                ->where('enable','=','eat')
                ->orderBy('class_id')->orderBy('order_date')->get();


        }else{
            $order_dates = $this->get_order_dates($semester);
            $i = 0;
            foreach ($order_dates as $k => $v) {
                if ($v == 1 and substr($k, 0, 7) == $lunch_orders[$lunch_order_id]) {
                    $this_order_dates[$i] = $k;
                    $i++;
                }
            }
            $order_data=array();
            $stu_order_datas = LunchStuDate::where('lunch_order_id', '=', $lunch_order_id)
                ->where('enable','=','eat')
                ->orderBy('class_id')->orderBy('order_date')->get();
        }

        foreach ($stu_order_datas as $stu_order_data) {
            if ($stu_order_data->p_id > 200 and $stu_order_data->p_id < 300 and $stu_order_data->eat_style != 3 and $stu_order_data->enable == "eat") {
                if ( ! isset($order_data[$stu_order_data->class_id][$stu_order_data->order_date]['w'])) {
                    $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['w'] = null;
                }
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['w']++;
            } elseif ($stu_order_data->p_id == 101 and $stu_order_data->eat_style != 3 and $stu_order_data->enable == "eat") {
                if ( ! isset($order_data[$stu_order_data->class_id][$stu_order_data->order_date]['g'])) {
                    $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['g'] = null;
                }
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['g']++;
            }elseif($stu_order_data->p_id == 301 and $stu_order_data->eat_style != 3 and $stu_order_data->enable == "eat"){
                if ( ! isset($order_data[$stu_order_data->class_id][$stu_order_data->order_date]['a'])) {
                    $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['a'] = null;
                }
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['a']++;
            }
        }


        $setup = $this->get_setup();
        $stud_money = $setup[$semester]['stud_money'];
        $stud_back_money = $setup[$semester]['stud_back_money'];
        $support_part_money = $setup[$semester]['support_part_money'];
        $support_all_money = $setup[$semester]['support_all_money'];

        //預定人數
        $class_people_data = LunchStuOrder::where('semester','=',$semester)
            ->where('eat_style','!=','3')
            ->where('out_in','=',null)
            ->orderBy('student_num')
            ->get();
        $class_people = [];
        foreach($class_people_data as $i){
            if(!isset($class_people[substr($i->student_num,0,3)])) $class_people[substr($i->student_num,0,3)] = 0;
            $class_people[substr($i->student_num,0,3)]++;
        }

        if($setup[$semester]['stud_money'] == '0') {
            $data = [
                'semester' => $semester,
                'lunch_orders' => $lunch_orders,
                'lunch_order_id' => $lunch_order_id,
                'this_order_dates' => $this_order_dates,
                'order_data' => $order_data,
                'support_all_money' => $support_all_money,
                'class_people' => $class_people,
            ];
            return view('lunch.report_master3-a',$data);
        }else{
            $data = [
                'semester' => $semester,
                'lunch_orders' => $lunch_orders,
                'lunch_order_id' => $lunch_order_id,
                'this_order_dates' => $this_order_dates,
                'order_data' => $order_data,
                'stud_money' => $stud_money,
                'stud_back_money' => $stud_back_money,
                'support_part_money' => $support_part_money,
                'support_all_money' => $support_all_money,
                'class_people' => $class_people,
            ];
            return view('lunch.report_master3',$data);
        }



    }


    public function stu(Request $request)
    {
        $is_tea ="";
        $is_admin = "";
        $class_id = "";
        $year_class_id = "";
        $select_date_menu = [];
        $stu_data=[];
        $stu_data2=[];

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

        //查新學期設好了沒
        $check_new_semester = LunchOrderDate::where('semester','=',$semester)->first();
        if(empty($check_new_semester)){
            $words = "新學期尚未設定好！";
            return view('errors.errors',compact('words'));
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
            //訂餐過的班級名單要用午餐這邊的
            $stu_datas2 = LunchStuOrder::where('semester','=',$semester)
            ->where('student_num','like',$class_id.'%')
            ->orderBy('student_num')
            ->get();
            foreach($stu_datas2 as $stu2){
                $stu_data2[substr($stu2->student_num,3,2)]['name'] = $stu2->student->name;
                $stu_data2[substr($stu2->student_num,3,2)]['sex'] = $stu2->student->sex;
                $stu_data2[substr($stu2->student_num,3,2)]['id'] = $stu2->student->id;
            }



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

        $setups = $this->get_setup();
        if($setups[$semester]['stud_money'] == '0'){
            $stu_default_p_id = "301";
        }else{
            $stu_default_p_id = "101";
        }

        $data = [
            'semester'=>$semester,
            'is_tea'=>$is_tea,
            'class_id'=>$class_id,
            'is_admin'=>$is_admin,
            'stu_data'=>$stu_data,
            'stu_data2'=>$stu_data2,
            'has_order'=>$has_order,
            'order_data'=>$order_data,
            'select_date'=>$select_date,
            'select_date_menu'=>$select_date_menu,
            'stu_default_p_id'=>$stu_default_p_id,
        ];


        return view('lunch.stu',$data);
    }

    public function stu_store(Request $request)
    {
        $semester = $request->input('semester');
        $eat_style = $request->input('eat_style');
        $p_id = $request->input('p_id');
        $student_num = $request->input('student_num');

        //這個學期各餐期的id
        $order_id_array = $this->get_order_id_array($semester);
        $order_dates = $this->get_order_dates($semester);

        $year_calss = YearClass::where('semester','=',$semester)->where('year_class','=',$request->input('class_id'))->first();

        $create_stu_date = [];
        $create_stu_order = [];
        foreach($order_dates as $k=>$v) {
            foreach ($year_calss->semester_students as $semester_student) {
                //轉出生不要在列
                if($semester_student->at_school == "1") {
                    $att['order_date'] = $k;
                    if ($v == "0") $att['enable'] = "not";
                    if ($v == "1") $att['enable'] = "eat";
                    $att['semester'] = $semester;
                    $att['lunch_order_id'] = $order_id_array[substr($k, 0, 7)];
                    $att['student_id'] = $semester_student->student_id;
                    $att['class_id'] = $request->input('class_id');
                    $att['num'] = $semester_student->num;
                    $att['p_id'] = $p_id[$semester_student->student_id];
                    $att['eat_style'] = $eat_style[$semester_student->student_id];
                    if ($att['eat_style'] == "3" and $v == "1") $att['enable'] = "no_eat";
                    //LunchStuDate::create($att);
                    $new_one = [
                        "order_date"=>$att['order_date'],
                        "enable"=>$att['enable'],
                        "semester"=>$att['semester'],
                        "lunch_order_id"=>$att['lunch_order_id'],
                        "student_id"=>$att['student_id'],
                        "class_id"=>$att['class_id'],
                        "num"=>$att['num'],
                        "p_id"=>$att['p_id'],
                        "eat_style"=>$att['eat_style'],
                    ];
                    array_push($create_stu_date, $new_one);

                }
            }
        }

        LunchStuDate::insert($create_stu_date);


        foreach($student_num as $k=>$v){
            $att2['semester'] = $semester;
            $att2['student_id'] = $k;
            $att2['student_num'] = $v;
            $att2['eat_style'] = $eat_style[$k];
            $att2['p_id'] = $p_id[$k];
            //LunchStuOrder::create($att2);
            $new_one = [
                "semester"=>$att2['semester'],
                "student_id"=>$att2['student_id'],
                "student_num"=>$att2['student_num'],
                "eat_style"=>$att2['eat_style'],
                "p_id"=>$att2['p_id'],
            ];
            array_push($create_stu_order, $new_one);

        }

        LunchStuOrder::insert($create_stu_order);


        return redirect()->route('lunch.stu');
    }

    public function stu_cancel(Request $request)
    {
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

        //查新學期設好了沒
        $check_new_semester = LunchOrderDate::where('semester','=',$semester)->first();
        if(empty($check_new_semester)){
            $words = "新學期尚未設定好！";
            return view('errors.errors',compact('words'));
        }

        //是否有學生停止退餐
        $setups = $this->get_setup();
        if($setups[$semester]['disable'] == "on") {
            $words = "本學期學生已停止退餐！！";
            return view('errors.errors', compact('words'));
        }


        if(Input::get('do') == "1"){

            $student_id = Input::get('student_id');
            $order_date = Input::get('order_date');

            //確認是否有逾期退餐
            $setups = $this->get_setup();
            $semester = $request->input('semester');

            $dt = Carbon::now();
            $die_date = $dt->addDays($setups[$semester]['die_line'])->toDateString();
            $first_date = str_replace ("-","",$order_date);
            $second_date = str_replace ("-","",$die_date);

            if($first_date < $second_date){
                $words = "({$order_date})已經逾期！請於請假日{$setups[$semester]['die_line']}天前請假！";
                return view('errors.errors',compact('words'));
            }



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

        $stu_data = [];


        if($year_class_id){
            /*
            $stu_datas = SemesterStudent::where('year_class_id', '=', $year_class_id)->where('at_school','=','1')->orderBy('num')->get();
            foreach ($stu_datas as $stu) {
                $stu_data[$stu->num]['name'] = $stu->student->name;
                $stu_data[$stu->num]['sex'] = $stu->student->sex;
                $stu_data[$stu->num]['id'] = $stu->student->id;
            }
            */
            //名單應該訂餐系統這邊給
            $stu_datas = LunchStuOrder::where('semester','=',$semester)
                ->where('student_num','like',$class_id.'%')
                ->orderBy('student_num')
                ->get();
            foreach($stu_datas as $stu){
                $stu_data[substr($stu->student_num,3,2)]['name'] = $stu->student->name;
                $stu_data[substr($stu->student_num,3,2)]['sex'] = $stu->student->sex;
                $stu_data[substr($stu->student_num,3,2)]['id'] = $stu->student->id;
                $stu_data[substr($stu->student_num,3,2)]['out_in'] = $stu->out_in;
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
                $order_data[$class_order->student_id][$class_order->order_date]['p_id'] = $class_order->p_id;
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

    public function check(Request $request)
    {
        //查目前學期
        $y = date('Y') - 1911;
        $array1 = array(8, 9, 10, 11, 12, 1);
        $array2 = array(2, 3, 4, 5, 6, 7);
        if (in_array(date('n'), $array1)) {
            if (date('n') == 1) {
                $this_semester = ($y - 1) . "1";
            } else {
                $this_semester = $y . "1";
            }
        } else {
            $this_semester = ($y - 1) . "2";
        }

        $semester = (empty($request->input('semester'))) ? $this_semester : $request->input('semester');
        $semesters = LunchSetup::orderBy('id')->pluck('semester', 'semester')->toArray();


        //查是不是導師
        if(auth()->user()->group_id =="4" or auth()->user()->group_id2 =="4"){
            $year_class_data = YearClass::where('semester','=',$semester)->where('user_id','=',auth()->user()->id)->first();
            if($year_class_data) {
                $class_id = $year_class_data->year_class;
                $checks = LunchCheck::where('semester','=',$semester)
                    ->where('user_id','=',auth()->user()->id)
                    ->where('class_id','=',$class_id)
                    ->orderBy('order_date','DESC')
                    ->get();
            }
            $is_admin = "";
            $mons ="";
        }

        //是不是管理人員
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if(!empty($check)){
            $mons = $this->get_order_id_array($semester);
            $is_admin =1 ;
            $class_id = "";
            $checks = LunchCheck::where('semester','=',$semester)
                ->orderBy('class_id')
                ->orderBy('order_date','DESC')
                ->get();
        }

        if(empty($class_id) and empty($is_admin)){
            $words = "你不是級任老師，也不是管理員！";
            return view('errors.errors',compact('words'));
        }

        $data = [
            'semester' => $semester,
            'semesters' =>$semesters,
            'class_id' =>$class_id,
            'checks' =>$checks,
            'is_admin' =>$is_admin,
            'mons'=>$mons,
        ];

        return view('lunch.check',$data);
    }

    public function check_store(Request $request)
    {
        if(empty($request->input('main_eat'))){
            $att['main_eat'] = 1 ;
        }
        if(empty($request->input('main_vag'))){
            $att['main_vag'] = 1 ;
        }
        if(empty($request->input('co_vag'))){
            $att['co_vag'] = 1 ;
        }
        if(empty($request->input('vag'))){
            $att['vag'] = 1 ;
        }
        if(empty($request->input('soup'))){
            $att['soup'] = 1 ;
        }

        if(empty($att)){
            $words = "每一項都合格不用回報！";
            return view('errors.errors',compact('words'));
        }

        if(empty($request->input('reason') )){
            $words = "請輸入不合格原因！";
            return view('errors.errors',compact('words'));
        }

        $dates = $this->get_order_dates($request->input('semester'));
        if($dates[$request->input('order_date')] != "1"){
            $words = $request->input('order_date') . " 該日沒有供餐！";
            return view('errors.errors',compact('words'));
        }

        $check = LunchCheck::where('class_id','=',$request->input('class_id'))
            ->where('order_date','=',$request->input('order_date'))
            ->first();
        if(!empty($check)){
            $words = $request->input('order_date') . " 該日已回報過！";
            return view('errors.errors',compact('words'));
        }


        $att['order_date'] = $request->input('order_date');
        $att['reason'] = $request->input('reason');
        $att['action'] = $request->input('action');
        $att['semester'] = $request->input('semester');
        $att['class_id'] = $request->input('class_id');
        $att['user_id'] = $request->input('user_id');

        LunchCheck::create($att);
        return redirect()->route('lunch.check');

    }

    public function check_destroy(LunchCheck $check)
    {
        $check->delete();
        return redirect()->route('lunch.check');
    }

    public function check_print(Request $request)
    {
        $semester = $request->input('semester');
        $mon = $request->input('mon');
        $dates = $this->get_order_dates($semester);

        $class_datas = YearClass::where('semester','=',$semester)
            ->orderBy('year_class')
            ->get();

        foreach($class_datas as $class_data){
            $class_tea[$class_data->year_class]['name'] = $class_data->name;
            if(!empty($class_data->user_id)) {
                $class_tea[$class_data->year_class]['tea'] = $class_data->user->name;
            }else{
                $class_tea[$class_data->year_class]['tea'] = "";
            }
        }

        $checks = LunchCheck::where('semester','=',$semester)
            ->orderBy('class_id')
            ->orderBy('order_date','DESC')
            ->get();

        foreach($checks as $check){
            $check_data[$check->class_id][$check->order_date]['main_eat'] = $check->main_eat;
            $check_data[$check->class_id][$check->order_date]['main_vag'] = $check->main_vag;
            $check_data[$check->class_id][$check->order_date]['co_vag'] = $check->co_vag;
            $check_data[$check->class_id][$check->order_date]['vag'] = $check->vag;
            $check_data[$check->class_id][$check->order_date]['soup'] = $check->soup;
            $check_data[$check->class_id][$check->order_date]['reason'] = $check->reason;
            if($check->action =="1"){
                $check_data[$check->class_id][$check->order_date]['action'] = "已處理";
            }
            if($check->action =="2"){
                $check_data[$check->class_id][$check->order_date]['action'] = "已更換";
            }
            if($check->action =="3"){
                $check_data[$check->class_id][$check->order_date]['action'] = "僅通報";
            }

        }

        $data = [
            'semester'=>$semester,
            'mon'=>$mon,
            'dates'=>$dates,
            'class_tea'=>$class_tea,
            'check_data'=>$check_data,
        ];
        return view('lunch.check_print',$data);
    }

    public function satisfaction(Request $request)
    {
        $is_admin ="";
        $class_id = "";

        //查目前學期
        $y = date('Y') - 1911;
        $array1 = array(8, 9, 10, 11, 12, 1);
        $array2 = array(2, 3, 4, 5, 6, 7);
        if (in_array(date('n'), $array1)) {
            if (date('n') == 1) {
                $this_semester = ($y - 1) . "1";
            } else {
                $this_semester = $y . "1";
            }
        } else {
            $this_semester = ($y - 1) . "2";
        }

        $semester = (empty($request->input('semester'))) ? $this_semester : $request->input('semester');
        $semesters = LunchSetup::orderBy('id')->pluck('semester', 'semester')->toArray();


        //查是不是導師
        if(auth()->user()->group_id =="4" or auth()->user()->group_id2 =="4"){
            $year_class_data = YearClass::where('semester','=',$semester)->where('user_id','=',auth()->user()->id)->first();
            if($year_class_data) {
                $class_id = $year_class_data->year_class;

            }
        }

        //是不是管理人員
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if(!empty($check)){
            $is_admin =1 ;
        }

        if(empty($class_id) and empty($is_admin)){
            $words = "你不是級任老師，也不是管理員！";
            return view('errors.errors',compact('words'));
        }

        $satisfactions = LunchSatisfaction::all();

        $data = [
            'is_admin' =>$is_admin,
            'class_id' =>$class_id,
            'semester' => $semester,
            'semesters' => $semesters,
            'satisfactions' => $satisfactions,
        ];
        return view('lunch.satisfaction',$data);
    }

    public function satisfaction_store(Request $request)
    {
        LunchSatisfaction::create($request->all());
        return redirect()->route('lunch.satisfaction');
    }

    public function satisfaction_destroy(LunchSatisfaction $satisfaction)
    {
        LunchSatisfactionClass::where('lunch_satisfaction_id','=',$satisfaction->id)
            ->delete();
        $satisfaction->delete();
        return redirect()->route('lunch.satisfaction');
    }

    public function satisfaction_show($id)
    {
        $satisfaction = LunchSatisfaction::where('id','=',$id)->first();

        //查是不是導師
        if(auth()->user()->group_id =="4" or auth()->user()->group_id2 =="4"){
            $year_class_data = YearClass::where('semester','=',$satisfaction->semester)->where('user_id','=',auth()->user()->id)->first();
            if($year_class_data) {
                $class_id = $year_class_data->year_class;

            }
        }

        if(empty($class_id)){
            $words = "你不是級任老師！";
            return view('errors.errors',compact('words'));
        }

        $student_people = LunchStuOrder::where('semester','=',$satisfaction->semester)
            ->where('student_num','like',$class_id.'%')
            ->where('eat_style','!=','3')
            ->count();

        $tea = LunchTeaDate::where('semester','=',$satisfaction->semester)
            ->where('user_id','=',auth()->user()->id)
            ->where('enable','=','eat')
            ->first();
        if(!empty($tea)){
            $tea_people = 1;
        }else{
            $tea_people = 0;
        }

        $class_people = $student_people + $tea_people;

        $data = [
            'satisfaction'=>$satisfaction,
            'class_id' =>$class_id,
            'class_people'=>$class_people,
        ];
        return view('lunch.satisfaction_show',$data);
    }

    public function satisfaction_show_store(Request $request)
    {
        $has_done = LunchSatisfactionClass::where('lunch_satisfaction_id','=',$request->input('lunch_satisfaction_id'))
            ->where('class_id','=',$request->input('class_id'))
            ->count();

        if($has_done > 0){
            $words = "該班填寫過了！";
            return view('errors.errors',compact('words'));
        }

        LunchSatisfactionClass::create($request->all());
        return redirect()->route('lunch.satisfaction');
    }

    public function satisfaction_show_answer($id)
    {
        $satisfaction = LunchSatisfaction::where('id','=',$id)->first();

        //查是不是導師
        if(auth()->user()->group_id =="4" or auth()->user()->group_id2 =="4"){
            $year_class_data = YearClass::where('semester','=',$satisfaction->semester)->where('user_id','=',auth()->user()->id)->first();
            if($year_class_data) {
                $class_id = $year_class_data->year_class;

            }
        }

        if(empty($class_id)){
            $words = "你不是級任老師！";
            return view('errors.errors',compact('words'));
        }

        $satisfaction_class = LunchSatisfactionClass::where('id','=',$id)
            ->where('class_id','=',$class_id)
            ->first();

        $total =
            $satisfaction_class->q1_1+
            $satisfaction_class->q1_2+
            $satisfaction_class->q1_3+
            $satisfaction_class->q1_4+
            $satisfaction_class->q1_5+
            $satisfaction_class->q2_1+
            $satisfaction_class->q2_2+
            $satisfaction_class->q3_1+
            $satisfaction_class->q3_2+
            $satisfaction_class->q3_3+
            $satisfaction_class->q3_4+
            $satisfaction_class->q3_5+
            $satisfaction_class->q3_6+
            $satisfaction_class->q3_7+
            $satisfaction_class->q3_8+
            $satisfaction_class->q3_9+
            $satisfaction_class->q3_10+
            $satisfaction_class->q4_1+
            $satisfaction_class->q4_2;

        $data = [
            'satisfaction_class'=>$satisfaction_class,
            'total'=>$total,
        ];
        return view('lunch.satisfaction_show_answer',$data);
    }

    public function satisfaction_help($id){
        //是不是管理人員
        $check = Fun::where('type', '=', '3')->where('username', '=', auth()->user()->username)->first();
        if(!empty($check)){
            $is_admin =1 ;
        }
        if(empty($is_admin)){
            $words = "你不是管理員！";
            return view('errors.errors',compact('words'));
        }

        $satisfaction = LunchSatisfaction::where('id','=',$id)->first();
        $satisfaction_class_data = LunchSatisfactionClass::where('lunch_satisfaction_id','=',$id)->get();
        foreach($satisfaction_class_data as $s_c){
            $has_done[$s_c->class_id] = 1;
        }


        $classes = YearClass::where('semester','=',$satisfaction->semester)->get();
        foreach($classes as $class_data){
            if(!isset($has_done[$class_data->year_class])){
                $student_people = LunchStuOrder::where('semester','=',$satisfaction->semester)
                    ->where('student_num','like',$class_data->year_class.'%')
                    ->where('eat_style','!=','3')
                    ->count();

                $tea = LunchTeaDate::where('semester','=',$satisfaction->semester)
                    ->where('user_id','=',$class_data->user_id)
                    ->where('enable','=','eat')
                    ->first();
                if(!empty($tea)){
                    $tea_people = 1;
                }else{
                    $tea_people = 0;
                }

                $class_people = $student_people + $tea_people;

                $att['class_people'] = $class_people;
                $att['q1_1'] = "3";
                $att['q1_2'] = "3";
                $att['q1_3'] = "3";
                $att['q1_4'] = "3";
                $att['q1_5'] = "3";
                $att['q2_1'] = "7";
                $att['q2_2'] = "7";
                $att['q3_1'] = "6";
                $att['q3_2'] = "6";
                $att['q3_3'] = "6";
                $att['q3_4'] = "6";
                $att['q3_5'] = "6";
                $att['q3_6'] = "6";
                $att['q3_7'] = "6";
                $att['q3_8'] = "6";
                $att['q3_9'] = "6";
                $att['q3_10'] = "6";
                $att['q4_1'] = "5";
                $att['q4_2'] = "6";
                $att['class_id'] = $class_data->year_class;
                $att['user_id'] = $class_data->user_id;
                $att['lunch_satisfaction_id'] = $id;
                LunchSatisfactionClass::create($att);
            }
        }

        return redirect()->route('lunch.satisfaction');

    }
    public function satisfaction_print($id){
        $satisfaction_classes_data = LunchSatisfactionClass::where('lunch_satisfaction_id','=',$id)
            ->orderBy('class_id')
            ->get();
        $favority = "";
        $suggest = "";
        foreach($satisfaction_classes_data as $satisfaction_class_data){
            $class_data[$satisfaction_class_data->class_id]['semester'] = $satisfaction_class_data->lunch_satisfaction->semester;
            $class_data[$satisfaction_class_data->class_id]['class_id'] = $satisfaction_class_data->class_id;
            $class_data[$satisfaction_class_data->class_id]['class_people'] = $satisfaction_class_data->class_people;
            $class_data[$satisfaction_class_data->class_id]['q1_1'] = $satisfaction_class_data->q1_1;
            $class_data[$satisfaction_class_data->class_id]['q1_2'] = $satisfaction_class_data->q1_2;
            $class_data[$satisfaction_class_data->class_id]['q1_3'] = $satisfaction_class_data->q1_3;
            $class_data[$satisfaction_class_data->class_id]['q1_4'] = $satisfaction_class_data->q1_4;
            $class_data[$satisfaction_class_data->class_id]['q1_5'] = $satisfaction_class_data->q1_5;
            $class_data[$satisfaction_class_data->class_id]['q2_1'] = $satisfaction_class_data->q2_1;
            $class_data[$satisfaction_class_data->class_id]['q2_2'] = $satisfaction_class_data->q2_2;
            $class_data[$satisfaction_class_data->class_id]['q3_1'] = $satisfaction_class_data->q3_1;
            $class_data[$satisfaction_class_data->class_id]['q3_2'] = $satisfaction_class_data->q3_2;
            $class_data[$satisfaction_class_data->class_id]['q3_3'] = $satisfaction_class_data->q3_3;
            $class_data[$satisfaction_class_data->class_id]['q3_4'] = $satisfaction_class_data->q3_4;
            $class_data[$satisfaction_class_data->class_id]['q3_5'] = $satisfaction_class_data->q3_5;
            $class_data[$satisfaction_class_data->class_id]['q3_6'] = $satisfaction_class_data->q3_6;
            $class_data[$satisfaction_class_data->class_id]['q3_7'] = $satisfaction_class_data->q3_7;
            $class_data[$satisfaction_class_data->class_id]['q3_8'] = $satisfaction_class_data->q3_8;
            $class_data[$satisfaction_class_data->class_id]['q3_9'] = $satisfaction_class_data->q3_9;
            $class_data[$satisfaction_class_data->class_id]['q3_10'] = $satisfaction_class_data->q3_10;
            $class_data[$satisfaction_class_data->class_id]['q4_1'] = $satisfaction_class_data->q4_1;
            $class_data[$satisfaction_class_data->class_id]['q4_2'] = $satisfaction_class_data->q4_2;
            $class_data[$satisfaction_class_data->class_id]['total'] =
                $class_data[$satisfaction_class_data->class_id]['q1_1']+
                $class_data[$satisfaction_class_data->class_id]['q1_2']+
                $class_data[$satisfaction_class_data->class_id]['q1_3']+
                $class_data[$satisfaction_class_data->class_id]['q1_4']+
                $class_data[$satisfaction_class_data->class_id]['q1_5']+
                $class_data[$satisfaction_class_data->class_id]['q2_1']+
                $class_data[$satisfaction_class_data->class_id]['q2_2']+
                $class_data[$satisfaction_class_data->class_id]['q3_1']+
                $class_data[$satisfaction_class_data->class_id]['q3_2']+
                $class_data[$satisfaction_class_data->class_id]['q3_3']+
                $class_data[$satisfaction_class_data->class_id]['q3_4']+
                $class_data[$satisfaction_class_data->class_id]['q3_5']+
                $class_data[$satisfaction_class_data->class_id]['q3_6']+
                $class_data[$satisfaction_class_data->class_id]['q3_7']+
                $class_data[$satisfaction_class_data->class_id]['q3_8']+
                $class_data[$satisfaction_class_data->class_id]['q3_9']+
                $class_data[$satisfaction_class_data->class_id]['q3_10']+
                $class_data[$satisfaction_class_data->class_id]['q4_1']+
                $class_data[$satisfaction_class_data->class_id]['q4_2'];
            $class_data[$satisfaction_class_data->class_id]['favority'] = $satisfaction_class_data->favority;
            if(!empty($satisfaction_class_data->favority))  $favority .= $satisfaction_class_data->favority."<br>";
            $class_data[$satisfaction_class_data->class_id]['suggest'] = $satisfaction_class_data->suggest;
            if(!empty($satisfaction_class_data->suggest)) $suggest .= $satisfaction_class_data->suggest."<br>";

            if(empty($satisfaction_class_data->user_id)){
                $class_data[$satisfaction_class_data->class_id]['teacher'] = "";
            }else{
                $class_data[$satisfaction_class_data->class_id]['teacher'] = $satisfaction_class_data->user->name;
            }
            $semester = $satisfaction_class_data->lunch_satisfaction->semester;
        }
        $data =[
            'class_data'=>$class_data,
            'semester' =>$semester,
            'favority' => $favority,
            'suggest' => $suggest,
        ];

        return view('lunch.satisfaction_show_print',$data);
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
            $lunch_setup[$setup->semester]['tea_open'] = $setup->tea_open;
            $lunch_setup[$setup->semester]['disable'] = $setup->disable;
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
