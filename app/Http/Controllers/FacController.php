<?php

namespace App\Http\Controllers;

use App\Fun;
use App\LunchOrder;
use App\LunchOrderDate;
use App\LunchStuDate;
use App\LunchTeaDate;
use Illuminate\Http\Request;

class FacController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //查目前學期
        $y = date('Y') - 1911;
        $array1 = array(8, 9, 10, 11, 12, 1);
        $array2 = array(2, 3, 4, 5, 6, 7);
        if (in_array(date('n'), $array1)) {
            if (date('n') == 1) {
                $this_semester = ($y - 1) . "1";
            } else {
                $semester = $y . "1";
            }
        } else {
            $semester = ($y - 1) . "2";
        }

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

        //教職
        $tea_order_datas = LunchTeaDate::where('lunch_order_id', '=', $lunch_order_id)
        ->where('enable','=','eat')
            ->orderBy('place','DESC')
            ->get();
        $last_place = "";
        $last_tea_date = "";
        dd($tea_order_datas);
        foreach ($tea_order_datas as $tea_order_data) {
            if ($last_place != $tea_order_data->place or $last_tea_date != $tea_order_data->order_date) {
                $tea_m = 0;
                $tea_g = 0;
            }
            if ($tea_order_data->eat_style == "1" and $tea_order_data->enable == "eat") {
                $tea_m++;
                $tea_order_data[$tea_order_data->place][$tea_order_data->order_date]['m'] = $tea_m;
            } elseif ($tea_order_data->eat_style == "2" and $tea_order_data->enable == "eat") {
                $tea_g++;
                $tea_order_data[$tea_order_data->place][$tea_order_data->order_date]['g'] = $tea_g;
            }
            $last_place = $tea_order_data->place;
            $last_tea_date = $tea_order_data->order_date;
        }



        //學生
        $stu_order_datas = LunchStuDate::where('lunch_order_id', '=', $lunch_order_id)
            ->where('eat_style','<>','3')
            ->orderBy('class_id')->orderBy('order_date')->get();
        $last_class = "";
        $last_date = "";

        foreach ($stu_order_datas as $stu_order_data) {
            if ($last_class != $stu_order_data->class_id or $last_date != $stu_order_data->order_date) {
                $m = 0;
                $g = 0;
            }
            if ($stu_order_data->eat_style == "1" and $stu_order_data->enable == "eat") {
                $m++;
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['m'] = $m;
            } elseif ($stu_order_data->eat_style == "2" and $stu_order_data->enable == "eat") {
                $g++;
                $order_data[$stu_order_data->class_id][$stu_order_data->order_date]['g'] = $g;
            }
            $last_class = $stu_order_data->class_id;
            $last_date = $stu_order_data->order_date;
        }


        $data = [
            'semester' => $semester,
            'lunch_orders' => $lunch_orders,
            'lunch_order_id' => $lunch_order_id,
            'this_order_dates' => $this_order_dates,
            'order_data' => $order_data,
            '$tea_order_data' => $tea_order_data,
        ];
        return view('lunch.report_fac', $data);
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
}
