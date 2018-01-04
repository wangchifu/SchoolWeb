@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1><img src="{{ asset('/img/lunch/check.png') }}" alt="學生退餐" width="50">供餐問題</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li><a href="{{ route('lunch.stu_cancel') }}">3.學生退餐</a></li>
        <li class="active"><a href="{{ route('lunch.check') }}">4.供餐問題</a></li>
        <li><a href="{{ route('lunch.satisfaction') }}">5.滿意度調查</a></li>
        <li><a href="{{ route('lunch.special') }}">6.特殊處理</a></li>
        <li><a href="{{ route('lunch.report') }}">7.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">8.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.check', 'method' => 'POST']) }}
                請先選擇學期：{{ Form::select('semester', $semesters, $semester, ['id' => 'semester', 'class' => 'form-control', 'placeholder' => '請先選擇學期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                @if(empty($is_admin))
                    <div class="panel-heading">
                        <h4>班級：{{ $class_id }}</h4>
                    </div>
                    <div class="panel-content">
                        <div class="bg-danger"><h2>1.不合格的，請將<img src="{{ asset('img/check.png') }}">點成<img src="{{ asset('img/no_check.png') }}"></h2></div>
                        <table class="table table-bordered">
                            <tr class="bg-primary">
                                <th>
                                    日期
                                </th>
                                <th>
                                    主食
                                </th>
                                <th>
                                    主菜
                                </th>
                                <th>
                                    副菜
                                </th>
                                <th>
                                    蔬菜
                                </th>
                                <th>
                                    湯品
                                </th>
                                <th>
                                    不合格原因
                                </th>
                                <th>
                                    廠商處置
                                </th>
                                <th>
                                    動作
                                </th>
                            </tr>
                            <tr class="bg-success">
                                {{ Form::open(['route'=>'lunch.check_store','method'=>'POST','id'=>'check_store','onsubmit'=>'return false']) }}
                                <td>
                                    <script src="{{ asset('js/cal/jscal2.js') }}"></script>
                                    <script src="{{ asset('js/cal/lang/cn.js') }}"></script>
                                    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/jscal2.css') }}">
                                    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/border-radius.css') }}">
                                    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/steel/steel.css') }}">
                                    <input id="order_date" name="order_date" class="form-control" placeholder ="請選供餐日" required="required" value="{{ date('Y-m-d') }}">
                                    <script>
                                        Calendar.setup({
                                            dateFormat : '%Y-%m-%d',
                                            inputField : 'order_date',
                                            trigger    : 'order_date',
                                            onSelect   : function() { this.hide();}
                                        });
                                        function goChangeBg1(obj){
                                            if (obj.checked == true){
                                                document.getElementById('main_eat').src="{{ asset('img/check.png') }}";
                                            }else{
                                                document.getElementById('main_eat').src="{{ asset('img/no_check.png') }}";
                                            }
                                        }
                                        function goChangeBg2(obj){
                                            if (obj.checked == true){
                                                document.getElementById('main_vag').src="{{ asset('img/check.png') }}";
                                            }else{
                                                document.getElementById('main_vag').src="{{ asset('img/no_check.png') }}";
                                            }
                                        }
                                        function goChangeBg3(obj){
                                            if (obj.checked == true){
                                                document.getElementById('co_vag').src="{{ asset('img/check.png') }}";
                                            }else{
                                                document.getElementById('co_vag').src="{{ asset('img/no_check.png') }}";
                                            }
                                        }
                                        function goChangeBg4(obj){
                                            if (obj.checked == true){
                                                document.getElementById('vag').src="{{ asset('img/check.png') }}";
                                            }else{
                                                document.getElementById('vag').src="{{ asset('img/no_check.png') }}";
                                            }
                                        }
                                        function goChangeBg5(obj){
                                            if (obj.checked == true){
                                                document.getElementById('soup').src="{{ asset('img/check.png') }}";
                                            }else{
                                                document.getElementById('soup').src="{{ asset('img/no_check.png') }}";
                                            }
                                        }
                                    </script>
                                </td>
                                <td>
                                    <input type="checkbox" name="main_eat" checked onclick="goChangeBg1(this);"><img id="main_eat" src="{{ asset('img/check.png') }}">
                                </td>
                                <td>
                                    <input type="checkbox" name="main_vag" checked onclick="goChangeBg2(this);"><img id="main_vag" src="{{ asset('img/check.png') }}">
                                </td>
                                <td>
                                    <input type="checkbox" name="co_vag" checked onclick="goChangeBg3(this);"><img id="co_vag" src="{{ asset('img/check.png') }}">
                                </td>
                                <td>
                                    <input type="checkbox" name="vag" checked onclick="goChangeBg4(this);"><img id="vag" src="{{ asset('img/check.png') }}">
                                </td>
                                <td>
                                    <input type="checkbox" name="soup" checked onclick="goChangeBg5(this);"><img id="soup" src="{{ asset('img/check.png') }}">
                                </td>
                                <td>
                                    {{ Form::text('reason',null,['id'=>'reason','class' => 'form-control', 'placeholder' => '請輸入原因','required'=>'required']) }}
                                </td>
                                <td>
                                    <?php
                                        $actives = [
                                            "1"=>"1.已處理(移除)",
                                            "2"=>"2.已更換",
                                            "3"=>"3.僅目前通報",
                                        ];
                                    ?>
                                    {{ Form::select('action', $actives, null, ['id' => 'action', 'class' => 'form-control']) }}
                                </td>
                                <td>
                                    <a href="#" class="btn btn-success" onclick="bbconfirm('check_store','你確定要送出嗎？')"><span class="glyphicon glyphicon-plus-sign"></span> 新增</a>
                                </td>
                                <input type="hidden" name="semester" value="{{ $semester }}">
                                <input type="hidden" name="class_id" value="{{ $class_id }}">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                {{ Form::close() }}
                            </tr>
                            <tr>
                                <td colspan="9"><h2>2.列出本學期本班回報記錄</h2></td>
                            </tr>
                            @foreach($checks as $check)
                                {{ Form::open(['route'=>['lunch.check_destroy',$check->id],'method'=>'POST','id'=>'check_destroy'.$check->id,'onsubmit'=>'return false']) }}
                            <tr>
                                <td>
                                    {{ $check->order_date }}
                                </td>
                                <td>
                                    @if($check->main_eat == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    @if($check->main_vag == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    @if($check->co_vag == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    @if($check->vag == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    @if($check->soup == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    {{ $check->reason }}
                                </td>
                                <td>
                                    @if($check->action == "1")
                                        已處理(移除)
                                    @elseif($check->action == "2")
                                        已更換
                                    @elseif($check->action == "3")
                                        僅目前通報
                                    @endif

                                </td>
                                <td>
                                    <a href="#" class="btn btn-danger" onclick="bbconfirm('check_destroy{{ $check->id }}','你真的要刪除？')">刪除</a>
                                </td>
                            </tr>
                                {{ Form::close() }}
                            @endforeach
                        </table>
                    </div>
                @else
                    <div class="panel-heading">
                        <h4>各班回報狀況</h4>
                    </div>
                    <div class="panel-content">
                        <table>
                        <tr>
                        @foreach($mons as $k=>$v)
                        {{ Form::open(['route'=>'lunch.check_print','method'=>'post','target'=>'_blank']) }}
                        <td>
                        <input type="hidden" name="semester" value="{{ $semester }}">
                        <input type="hidden" name="mon" value="{{ $k }}">
                        <button class="btn btn-success">列印 {{ $k }} 供餐檢核表</button>
                        </td>
                        {{ Form::close() }}
                        @endforeach
                        </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr class="bg-primary">
                                <th>
                                    班級
                                </th>
                                <th>
                                    日期
                                </th>
                                <th>
                                    主食
                                </th>
                                <th>
                                    主菜
                                </th>
                                <th>
                                    副菜
                                </th>
                                <th>
                                    蔬菜
                                </th>
                                <th>
                                    湯品
                                </th>
                                <th>
                                    不合格原因
                                </th>
                                <th>
                                    廠商處置
                                </th>
                                <th>
                                    動作
                                </th>
                            </tr>
                            @foreach($checks as $check)
                                {{ Form::open(['route'=>['lunch.check_destroy',$check->id],'method'=>'POST','id'=>'check_destroy'.$check->id,'onsubmit'=>'return false']) }}
                            <tr>
                                <td>
                                    {{ $check->class_id }}
                                </td>
                                <td>
                                    {{ $check->order_date }}
                                </td>
                                <td>
                                    @if($check->main_eat == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    @if($check->main_vag == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    @if($check->co_vag == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    @if($check->vag == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    @if($check->soup == "1")
                                        <img src="{{ asset('img/no_check.png') }}">
                                    @endif
                                </td>
                                <td>
                                    {{ $check->reason }}
                                </td>
                                <td>
                                    @if($check->action == "1")
                                        已處理(移除)
                                    @elseif($check->action == "2")
                                        已更換
                                    @elseif($check->action == "3")
                                        僅目前通報
                                    @endif

                                </td>
                                <td>
                                    <a href="#" class="btn btn-danger" onclick="bbconfirm('check_destroy{{ $check->id }}','你真的要刪除？')">刪除</a>
                                </td>
                            </tr>
                                {{ Form::close() }}
                            @endforeach
                        </table>
                    </div>
                @endif
            </div>


        </div>

@endsection
@include('layouts.partials.bootbox')