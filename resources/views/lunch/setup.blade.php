@extends('layouts.master')

@section('page-title', '系統管理 | 午餐系統')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="">2.學生訂餐</a></li>
        <li><a href="">3.供餐確認表</a></li>
        <li><a href="">4.滿意度調查</a></li>
        <li><a href="">5.報表輸出</a></li>
        <li class="active"><a href="{{ route('lunch.setup') }}">6.系統管理</a></li>
    </ul>
    <br>
    @if(empty($semester))
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>學期設定</h3>
                </div>
                <div class="panel-content">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-2">學期</th><th class="col-md-1">教職收費</th><th class="col-md-1">學生收費</th><th class="col-md-1">學生退費</th><th class="col-md-2">退餐幾日前</th><th class="col-md-2">六年級畢業日期</th><th>動作</th>
                        </thead>
                            </tr>
                        <tbody>
                        {{ Form::open(['route'=>'lunch.store_setup','method'=>'POST']) }}
                            <tr>
                                <td>
                                    {{ Form::text('semester',null,['id'=>'semester','class' => 'form-control', 'placeholder' => '學年學期：1061','required'=>'required','maxlength'=>'4']) }}
                                </td>
                                <td>
                                    {{ Form::text('tea_money',null,['id'=>'tea_money','class' => 'form-control', 'placeholder' => '數字','required'=>'required','maxlength'=>'4']) }}
                                </td>
                                <td>
                                    {{ Form::text('stud_money',null,['id'=>'stud_money','class' => 'form-control', 'placeholder' => '數字','required'=>'required','maxlength'=>'4']) }}
                                </td>
                                <td>
                                    {{ Form::text('stud_back_money',null,['id'=>'stud_back_money','class' => 'form-control', 'placeholder' => '數字','required'=>'required','maxlength'=>'4']) }}
                                </td>
                                <td>
                                    {{ Form::text('die_line',null,['id'=>'die_line','class' => 'form-control', 'placeholder' => '例：2 (天前)','required'=>'required','maxlength'=>'1']) }}
                                </td>
                                <td>
                                    {{ Form::text('stud_gra_date',null,['id'=>'stud_gra_date','class' => 'form-control', 'placeholder' => '2016-06-25','maxlength'=>'10']) }}
                                </td>
                                <td>
                                    <button class="btn btn-success">新增</button>
                                </td>
                            </tr>
                            {{ Form::close() }}
                        @foreach($lunch_setups as $k=>$lunch_setup)
                            {{ Form::open(['route'=>['lunch.update_setup',$lunch_setup->id],'method'=>'PATCH','id'=>'updateSetup'.$k,'onsubmit'=>'return false;']) }}
                            <tr>
                                <td>
                                    {{ Form::text('semester',$lunch_setup->semester,['id'=>'semester','class' => 'form-control', 'placeholder' => '學年學期：1061','required'=>'required','maxlength'=>'4','readonly'=>'readonly']) }}
                                </td>
                                <td>
                                    {{ Form::text('tea_money',$lunch_setup->tea_money,['id'=>'tea_money','class' => 'form-control', 'placeholder' => '數字','required'=>'required','maxlength'=>'4']) }}
                                </td>
                                <td>
                                    {{ Form::text('stud_money',$lunch_setup->stud_money,['id'=>'stud_money','class' => 'form-control', 'placeholder' => '數字','required'=>'required','maxlength'=>'4']) }}
                                </td>
                                <td>
                                    {{ Form::text('stud_back_money',$lunch_setup->stud_back_money,['id'=>'stud_back_money','class' => 'form-control', 'placeholder' => '數字','required'=>'required','maxlength'=>'4']) }}
                                </td>
                                <td>
                                    {{ Form::text('die_line',$lunch_setup->die_line,['id'=>'die_line','class' => 'form-control', 'placeholder' => '例：2 (天前)','required'=>'required','maxlength'=>'1']) }}
                                </td>
                                <td>
                                    {{ Form::text('stud_gra_date',$lunch_setup->stud_gra_date,['id'=>'stud_gra_date','class' => 'form-control', 'placeholder' => '2016-06-25','maxlength'=>'10']) }}
                                </td>
                                <td>
                                    <button class="btn btn-info" onclick="bbconfirm('updateSetup{{ $k }}','確定修改？')">修改</button>
                                    @if($has_order[$lunch_setup->semester])
                                        <a href="{{ route('lunch.show_order',$lunch_setup->semester) }}" class="btn btn-primary">觀看餐期</a>
                                    @else
                                    <a href="{{ route('lunch.create_order',$lunch_setup->semester) }}" class="btn btn-success">設定餐期</a>
                                    @endif
                                    <a href="{{ route('lunch.delete_setup',$lunch_setup->id) }}" id="DelLink{{ $k }}" class="btn btn-danger" onclick="bbconfirm2('DelLink{{ $k }}','你確定要刪除這學期的設定嗎？<br>若已有任何訂餐資料時，切勿刪除！')">整個刪除</a>
                                </td>
                            </tr>
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        @if(!empty($show_semester))
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>{{ $show_semester }} 學期供餐日期：共 {{ count($order_dates) }} 天</h3>
                        </div>
                        <div class="panel-content">
                            @foreach($semester_dates as $k1 =>$v1)
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>一</th><th>二</th><th>三</th><th>四</th><th>五</th><th class="bg-success">六</th><th class="bg-danger">日</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                <?php
                                    $first_d = explode("-",$v1[1]);
                                    $first_w = date("w",mktime(0,0,0,$first_d[1],$first_d[2],$first_d[0]));
                                    if($first_w==0) $first_w=7;

                                    for($k=1;$k<$first_w;$k++){
                                        if($k < 6){
                                            echo "<td></td>";
                                        }else{
                                            echo "<td class = \"bg-success\"></td>";
                                        }
                                    }
                                ?>
                                @foreach( $v1 as $k2=>$v2)
                                    <?php
                                        $d = explode("-",$v2);
                                        $w = date("w",mktime(0,0,0,$d[1],$d[2],$d[0]));
                                        if($w == "6"){
                                            $checked = "";
                                            $bgcolor = "bg-success";
                                        }elseif($w == "0"){
                                            $checked = "";
                                            $bgcolor = "bg-danger";
                                        }else{
                                            $checked = "checked";
                                            $bgcolor = "";
                                        }
                                    ?>
                                    <td class="{{ $bgcolor }}">{{ $v2 }}
                                        @if(!empty($order_dates[$v2]))
                                            <span class="btn btn-success btn-xs">供餐</span>
                                        @else
                                            <span class="btn btn-danger btn-xs">不供餐</span>
                                        @endif
                                    </td>
                                        <?php
                                        if($w == "0") echo "</tr><tr>";
                                        ?>
                                @endforeach
                            @endforeach
                                    </tr>
                                    </tbody>
                                </table>
                        </div>

                </div>
            </div>
        </div>
        @endif
    @else
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-default" onclick="history.back()">返回</button>
            <br>
            <br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>設定 {{ $semester }}學期 供餐日期</h3>
                </div>
                <div class="panel-content">
                    {{ Form::open(['route'=>'lunch.store_order','method'=>'POST']) }}
                    <?php $total_days = "";$i=1; ?>
                @foreach($semester_dates as $k1=>$v1)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th colspan="7">
                                    {{ $k1 }} 日期
                                </th>
                            </tr>
                            <tr>
                                <th>一</th><th>二</th><th>三</th><th>四</th><th>五</th><th class="bg-success">六</th><th class="bg-danger">日</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr align="center">
                                <?php
                                $first_d = explode("-",$v1[1]);
                                $first_w = date("w",mktime(0,0,0,$first_d[1],$first_d[2],$first_d[0]));
                                if($first_w==0) $first_w=7;

                                for($k=1;$k<$first_w;$k++){
                                    if($k < 6){
                                        echo "<td></td>";
                                    }else{
                                        echo "<td class = \"bg-success\"></td>";
                                    }
                                }
                                ?>
                                @foreach($v1 as $v2)
                                        <SCRIPT type='text/javascript'>
                                            function goChangeBg{{ $i }}(obj){
                                                if (obj.checked == true){
                                                    a= parseInt(document.getElementById('total_days').value);
                                                    a+=1;
                                                    document.getElementById('total_days').value=a;
                                                }else{
                                                    a= parseInt(document.getElementById('total_days').value);
                                                    a-=1;
                                                    document.getElementById('total_days').value=a;
                                                }
                                            }
                                        </SCRIPT>
                                    <?php
                                        $d = explode("-",$v2);
                                        $w = date("w",mktime(0,0,0,$d[1],$d[2],$d[0]));
                                        if($w == "6"){
                                            $checked = "";
                                            $bgcolor = "bg-success";
                                        }elseif($w == "0"){
                                            $checked = "";
                                            $bgcolor = "bg-danger";
                                        }else{
                                            $checked = "checked";
                                            $bgcolor = "";
                                            $total_days++;
                                        }
                                    ?>
                                    <td class="{{ $bgcolor }}">
                                        {{ $v2 }} 供餐<br><input id="checkbox{{ $i }}" type="checkbox" name="order_date[{{ $v2 }}]" style="zoom:250%" {{ $checked }} onclick="goChangeBg{{ $i }}(this)">
                                    </td>
                                    <?php
                                        if($w == "0") echo "</tr><tr align=\"center\">";
                                        $i++;
                                    ?>
                                @endforeach
                            </>
                            </tbody>
                        </table>
                @endforeach
                    <input name="semester" type="hidden" value="{{ $semester }}">
                    <button class="btn btn-success">送出</button>
                    {{ Form::close() }}
                    整學期共 <input type="text" id="total_days" value="{{ $total_days }}" size="2" readonly="readonly"> 天
                </div>
            </div>

        </div>

    </div>
    @endif
@endsection
@include('layouts.partials.bootbox')