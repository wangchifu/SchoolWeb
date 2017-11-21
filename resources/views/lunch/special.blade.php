@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1>特殊處理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li><a href="">3.供餐確認表</a></li>
        <li><a href="">4.滿意度調查</a></li>
        <li class="active"><a href="{{ route('lunch.special') }}">5.特殊處理</a></li>
        <li><a href="{{ route('lunch.report') }}">6.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">7.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.special', 'method' => 'POST']) }}
                請先選擇學期：{{ Form::select('semester', $semesters, $semester, ['id' => 'semester', 'class' => 'form-control', 'placeholder' => '請先選擇學期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>一、教師補訂餐</h4>
                </div>
                <div class="panel-content">
                    {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST','id'=>'order_tea','onsubmit'=>'return false;']) }}
                    <input type="hidden" name="op" value="order_tea">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>教師</th><th>學期</th><th>廠商</th><th class="col-md-1">葷素</th><th>取餐地點</th><th>導師班級(選填)</th><th>開始訂餐日</th><th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{ Form::select('user_id', $users, null, ['id' => 'user_id', 'class' => 'form-control', 'placeholder' => '請選擇老師','required' => 'required'])}}
                            </td>
                            <td>
                                {{ Form::text('semester',$semester, ['id' => 'semester', 'class' => 'form-control', 'readonly' => 'readonly']) }}
                            </td>
                            <td>
                                {{ Form::select('factory', $factorys,null, ['id' => 'factory', 'class' => 'form-control']) }}
                            </td>
                            <td>
                                <input name="eat_style" type="radio" value="1" checked> <span class="btn btn-danger btn-xs">葷食</span><br>
                                <input name="eat_style" type="radio" value="2"> <span class="btn btn-success btn-xs">素食</span>
                            </td>
                            <td>
                                <?php
                                $class = ['班級教室'=>'班級教室'];
                                $places2 = array_merge($places,$class);
                                ?>
                                {{ Form::select('place', $places2,null, ['id' => 'place', 'class' => 'form-control']) }}
                            </td>
                            <td>
                                {{ Form::text('classroom', null, ['id' => 'classroom', 'class' => 'form-control', 'placeholder' => '如：五年3班']) }}
                            </td>
                            <td>
                                <script src="{{ asset('js/cal/jscal2.js') }}"></script>
                                <script src="{{ asset('js/cal/lang/cn.js') }}"></script>
                                <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/jscal2.css') }}">
                                <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/border-radius.css') }}">
                                <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/steel/steel.css') }}">
                                <input id="b_order_date" name="b_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'b_order_date',
                                        trigger    : 'b_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="bbconfirm('order_tea','你確定新增嗎？有欄位沒填嗎？')">執行</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    {{ Form::close() }}
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>二、教職訂餐改變</h4>
                </div>
                <div class="panel-content">
                    {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST','id'=>'cancel_tea','onsubmit'=>'return false;']) }}
                    <input type="hidden" name="op" value="cancel_tea">
                    <table class="table">
                        <thead>
                        <tr><th>教師</th><th>訂餐日</th><th>更改</th></th><th>動作</th></tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{ Form::select('user_id', $users, null, ['id' => 'user_id', 'class' => 'form-control', 'placeholder' => '請選擇老師','required' => 'required'])}}
                            </td>
                            <td>
                                <input id="c_order_date" name="c_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'c_order_date',
                                        trigger    : 'c_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <?php
                                    $enable_selects = ['no_eat'=>'取消訂餐','eat'=>'又要訂餐']
                                ?>
                                {{ Form::select('enable', $enable_selects, null, ['id' => 'enable', 'class' => 'form-control'])}}
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="bbconfirm('cancel_tea','你確定要取消該師訂餐嗎？')">執行</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    {{ Form::close() }}
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>三、教職更改葷素或取餐地點</h4>
                </div>
                <div class="panel-content">
                    {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST','id'=>'change_tea','onsubmit'=>'return false;']) }}
                    <input type="hidden" name="op" value="change_tea">
                    <table class="table">
                        <thead>
                        <tr><th>教師</th><th>學期</th><th>更改事項</th><th>起始日</th><th>動作</th></tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{ Form::select('user_id', $users, null, ['id' => 'user_id', 'class' => 'form-control', 'placeholder' => '請選擇老師','required' => 'required'])}}
                            </td>
                            <td>
                                {{ Form::text('semester',$semester, ['id' => 'semester', 'class' => 'form-control', 'readonly' => 'readonly']) }}
                            </td>
                            <td>
                                <?php
                                $eat_style = ['eat_style2'=>'改吃素','eat_style1'=>'改吃葷'];
                                $change_selects = array_merge($eat_style,$places);
                                ?>
                                {{ Form::select('change', $change_selects, null, ['id' => 'change', 'class' => 'form-control'])}}
                            </td>
                            <td>
                                <input id="g_order_date" name="g_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'g_order_date',
                                        trigger    : 'g_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="bbconfirm('change_tea','你確定要更改該師訂餐資料嗎？')">執行</button>
                            </td>

                        </tr>

                        </tbody>

                    </table>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>四、教職</h4>
                </div>
                <div class="panel-content">
                    <table class="table">
                        <thead>
                        <tr><th>教師</th><th>學期</th><th>地點</th><th>改葷素起啟日</th><th>動作</th></tr>
                        </thead>

                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@include('layouts.partials.bootbox')