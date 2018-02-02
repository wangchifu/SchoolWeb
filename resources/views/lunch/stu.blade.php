@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1><img src="{{ asset('/img/lunch/stu.png') }}" alt="學生退餐" width="50">學生訂餐</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li class="active"><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li><a href="{{ route('lunch.stu_cancel') }}">3.學生退餐</a></li>
        <li><a href="{{ route('lunch.check') }}">4.供餐問題</a></li>
        <li><a href="{{ route('lunch.satisfaction') }}">5.滿意度調查</a></li>
        <li><a href="{{ route('lunch.special') }}">6.特殊處理</a></li>
        <li><a href="{{ route('lunch.report') }}">7.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">8.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>{{ $semester }} 學期 {{ $is_tea }}<?php if($has_order==1){echo "-訂餐完成！";}else{ echo "-尚未訂餐！";} ?></h3>
                    @if($is_admin==1)
                    {{ Form::open(['route' => 'lunch.stu', 'method' => 'POST']) }}
                    <input type="text" name="select_class" value="{{ $class_id }}" placeholder="3碼班級代號" maxlength="3"><button class="btn btn-success btn-xs">送出</button>
                    {{ Form::close() }}
                    @endif
                </div>
                <div class="panel-content">
                        @if(empty($has_order))
                            @if($stu_data)
                            {{ Form::open(['route'=>'lunch.stu_store','method'=>'POST','id'=>'stu_store','onsubmit'=>'return false;']) }}
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>座號</th><th>姓名</th><th colspan="3">葷素食</th><th>學生身份</th><th>座號</th><th>姓名</th><th>速看</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stu_data as $k=>$v)
                                    <SCRIPT type='text/javascript'>
                                        function goChangeBgm{{ $k }}(obj){
                                            if (obj.checked == true){
                                                document.imgm{{ $k }}.src='../img/meat.png';
                                                document.imgg{{ $k }}.src='../img/no_color.png';
                                                document.imgx{{ $k }}.src='../img/no_color.png';
                                                document.quick_eat{{ $k }}.src='../img/no_color.png';

                                            }
                                        }
                                        function goChangeBgg{{ $k }}(obj){
                                            if (obj.checked == true){
                                                document.imgm{{ $k }}.src='../img/no_color.png';
                                                document.imgg{{ $k }}.src='../img/vegetarian.png';
                                                document.imgx{{ $k }}.src='../img/no_color.png';
                                                document.quick_eat{{ $k }}.src='../img/lettuce.png';

                                            }
                                        }
                                        function goChangeBgx{{ $k }}(obj){
                                            if (obj.checked == true){
                                                document.imgm{{ $k }}.src='../img/no_color.png';
                                                document.imgg{{ $k }}.src='../img/no_color.png';
                                                document.imgx{{ $k }}.src='../img/no_check.png';
                                                document.quick_eat{{ $k }}.src='../img/delete.png';
                                            }
                                        }
                                        function goChangep{{ $k }}(obj){
                                            if (obj.value >200){
                                                document.quick_p{{ $k }}.src='../img/face_smile.png';
                                            }else{
                                                document.quick_p{{ $k }}.src='../img/no_color.png';
                                            }
                                        }
                                    </SCRIPT>
                                    @if($v['sex']==1)
                                        <?php $color="text-primary";$icon="boy.gif"; ?>
                                    @elseif($v['sex']==2)
                                        <?php $color="text-danger";$icon="girl.gif"; ?>
                                    @endif
                                <tr>
                                    <td>{{ $k }}</td>
                                    <td><img src="{{ asset('img/'.$icon) }}"><span class="{{ $color }}">{{ $v['name'] }}</span></td>
                                    <td><input type='radio' name='eat_style[{{ $v['id'] }}]' id="style1{{ $k }}" value='1' checked onclick='goChangeBgm{{ $k }}(this)' ><span class="btn btn-danger btn-xs" onclick="getElementById('style1{{ $k }}').checked='true';goChangeBgm{{ $k }}(getElementById('style1{{ $k }}'))">葷食</span><img src="{{ asset('img/meat.png') }}" name="imgm{{ $k }}" width="16"></td>
                                    <td><input type='radio' name='eat_style[{{ $v['id'] }}]' id="style2{{ $k }}" value='2'  onclick='goChangeBgg{{ $k }}(this)' ><span class="btn btn-success btn-xs" onclick="getElementById('style2{{ $k }}').checked='true';goChangeBgg{{ $k }}(getElementById('style2{{ $k }}'))">素食</span><img src="{{ asset('img/no_color.png') }}" name="imgg{{ $k }}" width="16"></td>
                                    <td><input type='radio' name='eat_style[{{ $v['id'] }}]' id="style3{{ $k }}" value='3'  onclick='goChangeBgx{{ $k }}(this)' ><span class="btn btn-default btn-xs" onclick="getElementById('style3{{ $k }}').checked='true';goChangeBgx{{ $k }}(getElementById('style3{{ $k }}'))">不訂餐</span><img src="{{ asset('img/no_color.png') }}" name="imgx{{ $k }}" width="16"></td>
                                    <td>
                                        <?php
                                            $selects = [
                                                '101'=>"100-----一般生",
                                                '201'=>"201-----弱勢生-----低收入戶",
                                                '202'=>"202-----弱勢生-----中低收入戶",
                                                '203'=>"203-----弱勢生-----家庭突發因素",
                                                '204'=>"204-----弱勢生-----父母一方失業",
                                                '205'=>"205-----弱勢生-----單親家庭",
                                                '206'=>"206-----弱勢生-----隔代教養",
                                                '207'=>"207-----弱勢生-----特殊境遇",
                                                '208'=>"208-----弱勢生-----身心障礙學生",
                                                '209'=>"209-----弱勢生-----新住民子女",
                                                '210'=>"210-----弱勢生-----原住民子女",
                                            ];
                                        ?>
                                        {{ Form::select('p_id['.$v['id'].']', $selects, null, ['id' => 'p_id', 'class' => 'form-control','onchange'=>'goChangep'.$k.'(this)']) }}
                                    </td>
                                    <td>{{ $k }}</td>
                                    <td><span class="{{ $color }}">{{ $v['name'] }}</span></td>
                                    <td><img src="{{ asset('img/no_color.png') }}" name="quick_eat{{ $k }}"><img src="{{ asset('img/no_color.png') }}" name="quick_p{{ $k }}"></td>
                                </tr>
                                    <input type="hidden" name="student_num[{{ $v['id'] }}]" value="{{ $class_id.$k }}">
                                @endforeach
                                <input type="hidden" name="semester" value="{{ $semester }}">
                                <input type="hidden" name="class_id" value="{{ $class_id }}">
                                <tr><td><button class="btn btn-info" id="b_submit" onclick="bbconfirm3('stu_store','確定送出訂單？按確定後，請等待一下！！')">送出訂單</button></td></tr>
                                {{ Form::close() }}
                            @endif
                        @else
                            {{ Form::open(['route' => 'lunch.stu', 'method' => 'POST']) }}
                            {{ Form::select('select_date', $select_date_menu, $select_date, ['id' => 'select_date', 'class' => 'form-control', 'placeholder' => '請選擇日期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                            <input type="hidden" name="select_class" value="{{ $class_id }}">
                            {{ Form::close() }}
                            <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>座號</th><th>姓名</th><th colspan="3">葷素食</th><th>學生身份</th><th>座號</th><th>姓名</th><th>速看</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stu_data2 as $k=>$v)
                                <tr>
                                    @if($v['sex']==1)
                                        <?php $color="text-primary";$icon="boy.gif"; ?>
                                    @elseif($v['sex']==2)
                                        <?php $color="text-danger";$icon="girl.gif"; ?>
                                    @endif
                                    <td>{{ $k }}</td>
                                    <td><img src="{{ asset('img/'.$icon) }}"><span class="{{ $color }}">{{ $v['name'] }}</span></td>
                                    <td colspan="2">
                                        <?php
                                            //查詢日期不在餐期區間
                                            if(empty($order_data[$select_date])){
                                                header('location:stu');
                                                die();
                                            }
                                            if($order_data[$select_date][$v['id']]['eat_style']=="1"){
                                                $mess1 = "<span class=\"btn btn-danger btn-xs\">葷食</span><img src=\"../img/meat.png\">";
                                                $mess2 = "<img src=\"../img/no_color.png\" width=\"16\">";
                                            }elseif($order_data[$select_date][$v['id']]['eat_style']=="2"){
                                                $mess1 = "<span class=\"btn btn-success btn-xs\">素食</span><img src=\"../img/vegetarian.png\">";
                                                $mess2 = "<img src=\"../img/lettuce.png\">";
                                            }elseif($order_data[$select_date][$v['id']]['eat_style']=="3"){
                                                $mess1 = "<span class=\"btn btn-default btn-xs\">不訂餐</span><img src=\"../img/no_check.png\">";
                                                $mess2 = "<img src=\"../img/delete.png\">";
                                            }
                                            if($order_data[$select_date][$v['id']]['p_id'] > 200){
                                                $mess3 = "<img src=\"../img/face_smile.png\">";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "201") $p = "低收入戶";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "202") $p = "中低收入戶";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "203") $p = "家庭突發因素";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "204") $p = "父母一方失業";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "205") $p = "單親家庭";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "206") $p = "隔代教養";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "207") $p = "特殊境遇";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "208") $p = "身心障礙學生";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "209") $p = "新住民子女";
                                                if($order_data[$select_date][$v['id']]['p_id'] == "210") $p = "原住民子女";

                                            }else{
                                                $mess3 = "<img src=\"../img/no_color.png\">";
                                                $p="一般生";
                                            }
                                            if($order_data[$select_date][$v['id']]['enable']=="eat"){
                                                $enable = "訂餐";
                                            }elseif($order_data[$select_date][$v['id']]['enable']=="not"){
                                                $enable = "未供餐";
                                            }elseif($order_data[$select_date][$v['id']]['enable']=="back"){
                                                $enable = "請假退費";
                                            }elseif($order_data[$select_date][$v['id']]['enable']=="out"){
                                                $enable = "轉出已退費";
                                            }elseif($order_data[$select_date][$v['id']]['enable']=="no_eat"){
                                                $enable = "學生沒有訂餐";
                                            }

                                        ?>
                                        {!! $mess1 !!}
                                    </td>
                                    <td>
                                        {{ $enable }}
                                    </td>
                                    <td>{{ $p }}</td>
                                    <td>{{ $k }}</td>
                                    <td><span class="{{ $color }}">{{ $v['name'] }}</span></td>
                                    <td>{!! $mess2 !!}{!! $mess3 !!}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#b_submit").click(function(){
            $("#b_submit").hide();
        });
    </script>
@endsection
@include('layouts.partials.bootbox')