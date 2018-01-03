@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1><img src="{{ asset('/img/lunch/special.png') }}" alt="學生退餐" width="50">特殊處理<span class="text-danger">(不熟悉不得操作)</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li><a href="{{ route('lunch.stu_cancel') }}">3.學生退餐</a></li>
        <li><a href="{{ route('lunch.check') }}">4.供餐問題</a></li>
        <li><a href="">5.滿意度調查</a></li>
        <li class="active"><a href="{{ route('lunch.special') }}">6.特殊處理</a></li>
        <li><a href="{{ route('lunch.report') }}">7.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">8.系統管理</a></li>
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
                                {{ Form::text('classroom', null, ['id' => 'classroom', 'class' => 'form-control', 'placeholder' => '如：503']) }}
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
                                <button class="btn btn-success" onclick="bbconfirm('order_tea','你確定新增嗎？有欄位沒填嗎？')">執行教職補訂</button>
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
                                <button class="btn btn-success" onclick="bbconfirm('cancel_tea','你確定要取消該師訂餐嗎？')">執行教職取消(重訂)餐</button>
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
                                <button class="btn btn-success" onclick="bbconfirm('change_tea','你確定要更改該師訂餐資料嗎？')">執行教職更改葷素地點</button>
                            </td>

                        </tr>

                        </tbody>

                    </table>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>四、更改學生期初訂餐資料(期中切勿操作)</h4>
                </div>
                <div class="panel-content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                班級座號
                            </th>
                            <th>
                                學期
                            </th>
                            <th class="col-md-3">
                                葷素?
                            </th>
                            <th>
                                學生身份
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        </thead>
                        <tr>
                            {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST']) }}
                            <input type="hidden" name="op" value="change_one_stud">
                            <td>
                                {{ Form::text('student_num',null, ['id' => 'num', 'class' => 'form-control',"maxlength"=>"5", 'placeholder' => '班級座號5碼', 'required' => 'required']) }}
                            </td>
                            <td>
                                {{ Form::text('semester',$semester, ['id' => 'semester', 'class' => 'form-control', 'readonly' => 'readonly']) }}
                            </td>
                            <td>
                                <input type="radio" name="eat_style" value="1" checked><span class="btn btn-danger btn-xs">葷食</span>　　<input type="radio" name="eat_style" value="2"><span class="btn btn-success btn-xs">素食</span>　　<input type="radio" name="eat_style" value="3"><span class="btn btn-default btn-xs">不訂</span>
                            </td>
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
                            <td>
                                {{ Form::select('p_id', $selects, null, ['id' => 'p_id', 'class' => 'form-control']) }}
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="if(confirm('您確定送出嗎?')) return true;else return false">執行更改學生訂餐</button>
                            </td>
                            {{ Form::close() }}
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>五、班級或學年學生單日退餐</h4>
                </div>
                <div class="panel-content">
                    <table class="table">
                        <thead>
                        <tr><th>項目</th><th>來源</th><th>退餐日</th><th>動作</th></tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                單一班級單日退餐退費(疫情或班級活動)
                            </td>
                            {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST','id'=>'change_stu1','onsubmit'=>'return false;']) }}
                            <input type="hidden" name="op" value="change_stu1">
                            <td>
                                {{ Form::text('select_class',null, ['id' => 'select_calss', 'class' => 'form-control','placeholder'=>'3碼班級代號：101代表一年1班','maxlength'=>'3']) }}
                            </td>
                            <td>
                                <input id="stu1_order_date" name="stu1_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'stu1_order_date',
                                        trigger    : 'stu1_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="bbconfirm('change_stu1','你確定要更改該班級訂餐資料嗎？')">執行班級退餐退費</button>
                            </td>
                            {{ Form::close() }}
                        </tr>
                        <tr>
                            <td>
                                單一學年單日退餐退費(戶外教學等學年活動)
                            </td>
                            {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST','id'=>'change_stu2','onsubmit'=>'return false;']) }}
                            <input type="hidden" name="op" value="change_stu2">
                            <td>
                                {{ Form::text('select_year',null, ['id' => 'select_year', 'class' => 'form-control','placeholder'=>'1碼學年代號：1代表一年級','maxlength'=>'1']) }}
                            </td>
                            <td>
                                <input id="stu2_order_date" name="stu2_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'stu2_order_date',
                                        trigger    : 'stu2_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="bbconfirm('change_stu2','你確定要更改該學年訂餐資料嗎？')">執行學年退餐退費</button>
                            </td>
                            {{ Form::close() }}
                        </tr>
                        <tr>
                            <td>
                                單一學年單日退餐<span class="text-danger">不退費</span>(學年戶外教學，收費時已扣除了，不退費！)
                            </td>
                            {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST','id'=>'change_stu2-2','onsubmit'=>'return false;']) }}
                            <input type="hidden" name="op" value="change_stu2-2">
                            <td>
                                {{ Form::text('select_year',null, ['id' => 'select_year', 'class' => 'form-control','placeholder'=>'1碼學年代號：1代表一年級','maxlength'=>'1']) }}
                            </td>
                            <td>
                                <input id="stu2-2_order_date" name="stu2-2_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'stu2-2_order_date',
                                        trigger    : 'stu2-2_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="bbconfirm('change_stu2-2','你確定要更改該學年訂餐資料嗎？')">執行學年退餐不退費</button>
                            </td>
                            {{ Form::close() }}
                        </tr>
                        <tr>
                            <td>
                                全校師生單日退餐退費(颱風)
                            </td>
                            {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST','id'=>'change_stu3','onsubmit'=>'return false;']) }}
                            <input type="hidden" name="op" value="change_stu3">
                            <td>

                            </td>
                            <td>
                                <input id="stu3_order_date" name="stu3_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'stu3_order_date',
                                        trigger    : 'stu3_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="bbconfirm('change_stu3','你確定要更改全校師生訂餐資料嗎？')">執行全校師生扣餐</button>
                            </td>
                            {{ Form::close() }}
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>六、轉入生處理(或是原本不訂，突然要訂)，開始用餐日之前不計退費</h4>
                </div>
                <div class="panel-content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                班級座號
                            </th>
                            <th>
                                學期
                            </th>
                            <th>
                                學號
                            </th>
                            <th>
                                姓名
                            </th>
                            <th class="col-md-1">
                                性別
                            </th>
                            <th class="col-md-1">
                                轉入<br>or<br>又訂
                            </th>
                            <th class="col-md-1">
                                葷<br>or<br>素
                            </th>
                            <th class="col-md-2">
                                學生身份
                            </th>
                            <th class="col-md-2">
                                訂餐日
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        </thead>
                        <tr>
                            {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST']) }}
                            <input type="hidden" name="op" value="in_stud">
                            <td>
                                {{ Form::text('student_num',null, ['id' => 'num', 'class' => 'form-control',"maxlength"=>"5", 'placeholder' => '班級座號5碼', 'required' => 'required']) }}
                            </td>
                            <td>
                                {{ Form::text('semester',$semester, ['id' => 'semester', 'class' => 'form-control', 'readonly' => 'readonly']) }}
                            </td>
                            <td>
                                {{ Form::text('sn',null, ['id' => 'sn', 'class' => 'form-control',"maxlength"=>"6", 'placeholder' => '學號6碼', 'required' => 'required']) }}
                            </td>
                            <td>
                                {{ Form::text('name',null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '學生姓名', 'required' => 'required']) }}
                            </td>
                            <td>
                                <?php $stud_sex = [1=>'男',2=>'女']; ?>
                                {{ Form::select('sex', $stud_sex, null, ['id' => 'sex', 'class' => 'form-control','required'=>'required']) }}
                            </td>
                            <td>
                                <input type="radio" name="type" value="in" checked>轉入<br><input type="radio" name="type" value="eat">又訂
                            </td>
                            <td>
                                <input type="radio" name="eat_style" value="1" checked><span class="btn btn-danger btn-xs">葷食</span><br><input type="radio" name="eat_style" value="2"><span class="btn btn-success btn-xs">素食</span><br><input type="radio" name="eat_style" value="3"><span class="btn btn-default btn-xs">不訂</span>
                            </td>
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
                            <td>
                                {{ Form::select('p_id', $selects, null, ['id' => 'p_id', 'class' => 'form-control']) }}
                            </td>
                            <td>
                                <input id="in_stud_order_date" name="in_stud_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'in_stud_order_date',
                                        trigger    : 'in_stud_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="if(confirm('您確定送出嗎?')) return true;else return false">執行轉入生</button>
                            </td>
                            {{ Form::close() }}
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>七、轉出生處理(或是原本有訂，突然不訂)，不用餐日起要退費</h4>
                </div>
                <div class="panel-content">
                    <table class="table">
                        <thead>
                        <tr><th>班級座號代碼</th><th>學期</th><th>轉出 or 不訂</th><th>不訂餐開始日</th><th>動作</th></tr>
                        </thead>
                        <tbody>
                        <tr>
                            {{ Form::open(['route' => 'lunch.do_special', 'method' => 'POST']) }}
                            <input type="hidden" name="op" value="out_stud">
                            <td>
                                {{ Form::text('student_num',null, ['id' => 'student_num', 'class' => 'form-control', 'required' => 'required','maxlength'=>'5', 'placeholder' => '班級座號5碼']) }}
                            </td>
                            <td>
                                {{ Form::text('semester',$semester, ['id' => 'semester', 'class' => 'form-control', 'readonly' => 'readonly']) }}
                            </td>
                            <td>
                                <input type="radio" name="type" value="out" checked>轉出 <input type="radio" name="type" value="no_eat">不訂
                            </td>
                            <td>
                                <input id="out_stud_order_date" name="out_stud_order_date" class="form-control" placeholder ="請選起始日" required="required" value="{{ date('Y-m-d') }}">
                                <script>
                                    Calendar.setup({
                                        dateFormat : '%Y-%m-%d',
                                        inputField : 'out_stud_order_date',
                                        trigger    : 'out_stud_order_date',
                                        onSelect   : function() { this.hide();}
                                    });
                                </script>
                            </td>
                            <td>
                                <button class="btn btn-success" onclick="if(confirm('您確定送出嗎?')) return true;else return false">執行轉出生</button>
                            </td>
                            {{ Form::close() }}
                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>八、學生多人多日退餐退費(球隊比賽)</h4>
                </div>
                <div class="panel-content">
                    <table class="table">
                        <thead>
                        <tr><th>學生(班級座號5碼,請假日期)</th></tr>
                        </thead>
                        <tbody>
                        <tr>
                            {{ Form::open(['route' => ['lunch.do_special'], 'method' => 'POST','id'=>'change_studs','onsubmit'=>'return false;']) }}
                            <input type="hidden" name="op" value="change_studs">
                            <td>
                                <textarea name="studs_data" class="form-control" placeholder="10101,2017-12-01"></textarea>
                            </td>
                            {{ Form::close() }}
                        </tr>
                        <tr>
                            <td>
                                <button class="btn btn-success" onclick="bbconfirm('change_studs','你確定要更改這些學生的訂餐資料嗎？')">執行多筆</button>
                            </td>
                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@include('layouts.partials.bootbox')