@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1><img src="{{ asset('/img/lunch/tea.png') }}" alt="學生退餐" width="50">教職員訂餐</h1>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li><a href="{{ route('lunch.stu_cancel') }}">3.學生退餐</a></li>
        <li><a href="">4.供餐確認表</a></li>
        <li><a href="">5.滿意度調查</a></li>
        <li><a href="{{ route('lunch.special') }}">6.特殊處理</a></li>
        <li><a href="{{ route('lunch.report') }}">7.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">8.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            @if(empty($semester))
            <div class="well">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>學期</th><th>單餐費用</th><th>訂餐日數</th><th>收費</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tea_count_semesters as $k=>$v)
                        {{ Form::open(['route' => 'lunch.index', 'method' => 'POST']) }}
                        <tr><td>{{ $k }} <button class="btn btn-success btn-xs">訂餐處理</button></td><td>{{ $setups[$k]['tea_money'] }}</td><td>{{ $v }}</td><td>{{ $setups[$k]['tea_money']*$v }}</td></tr>
                        <input type="hidden" name="semester" value="{{ $k }}">
                        {{ Form::close() }}
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @if(!empty($semester))
            <button class="btn btn-default" onclick="history.back()">返回</button>
            <br>
                <?php
                $i=1;
                $total_order_dates = "0";
                foreach($tea_dates as $v){
                    if($v == "1" or $v=="eat") $total_order_dates++;
                }
                ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if($user_has_order=="0")
                    <h3>請依序選擇你 {{ $semester }} 學期訂餐資訊後，在最下方「送出」</h3>
                    @else
                    <h3>你 {{ $semester }} 學期訂餐日期如下，共 {{ $total_order_dates }} 天</h3>
                    @endif
                </div>
                <div class="panel-content">
                @if($total_order_dates != "0")
                        @if($user_has_order =="1")
                        <div>
                            <script src="{{ asset('js/cal/jscal2.js') }}"></script>
                            <script src="{{ asset('js/cal/lang/cn.js') }}"></script>
                            <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/jscal2.css') }}">
                            <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/border-radius.css') }}">
                            <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/steel/steel.css') }}">
                            {{ Form::open(['route'=>'lunch.del_tea_date','method'=>'POST']) }}
                            取消訂餐：<input type="text" id="del_tea_date" name="del_tea_date" maxlength="10" required><button class="btn btn-danger btn-xs" onclick="if(confirm('您確定取消嗎?')) return true;else return false"">確認取消</button> ( {{ $setups[$semester]['die_line'] }} 天前方可取消訂餐)
                            <input type="hidden" name="semester" value="{{ $semester }}">
                            {{ Form::close() }}
                            <script>
                                Calendar.setup({
                                    dateFormat : '%Y-%m-%d',
                                    inputField : 'del_tea_date',
                                    trigger    : 'del_tea_date',
                                    onSelect   : function() { this.hide();}
                                });
                            </script>
                        </div>
                        @endif
                    <br>
                {{ Form::open(['route'=>'lunch.store_tea_date','method'=>'POST']) }}
                            <div class="form-group">
                                <label for="title">(1)供餐廠商：</label>
                                @if($user_has_order =="1")
                                    {{ $setups[$semester]['factory'] }}
                                @else
                                    <input name="factory" value="{{ $setups[$semester]['factory'] }}" readonly="readonly">
                                @endif
                            </div>
                            <div class="form-group">
                            <label for="title">(2)葷素食：</label>
                                @if($user_has_order =="1")
                                    @if($user_eat_style =="1")
                                        <span class="btn btn-danger btn-xs">葷食</span> (欲改葷素食請通知管理者)
                                    @endif
                                    @if($user_eat_style =="2")
                                            <span class="btn btn-success btn-xs">素食</span> (欲改葷素食請通知管理者)
                                    @endif
                                @else
                                    <input name="eat_style" type="radio" id='style1' value="1" checked><span class="btn btn-danger btn-xs" onclick="getElementById('style1').checked='true';">葷食</span>　　　<input name="eat_style" type="radio" id='style2' value="2"><span class="btn btn-success btn-xs" onclick="getElementById('style2').checked='true';">素食</span><br>
                                @endif
                            </div>
                        <?php
                            $places = explode(',',$setups[$semester]['place']);
                            foreach($places as $k=>$v){
                                $place_array[$v] = $v;
                            }
                        ?>
                            <div class="form-group">
                                <label for="title">(3)取餐地點：</label>
                                @if($user_has_order =="1")
                                {{ $user_place }} (欲改地點請通知管理者)
                                @else
                                    @if($has_class_tea)
                                        {{ Form::text('place', $has_class_tea, ['id' => 'place', 'readonly'=>'readonly']) }}
                                    @else
                                        {{ Form::select('place', $place_array, null, ['id' => 'place', 'placeholder' => '請選擇用餐地點','required'=>'required']) }}
                                    @endif
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="title">(4)訂餐日期：</label>
                            </div>

                @foreach($semester_dates as $k1=>$v1)
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
                    @foreach($v1 as $k2=>$v2)
                            <?php
                            $d = explode("-",$v2);
                            $w = date("w",mktime(0,0,0,$d[1],$d[2],$d[0]));
                            if($w == "6"){
                                $bgcolor = "bg-success";
                            }elseif($w == "0"){
                                $bgcolor = "bg-danger";
                            }else{
                                $bgcolor = "";
                            }
                            if($order_dates[$v2]=="1"){
                                $checked = "checked";
                            }else{
                                $checked = "";
                            }
                            ?>
                            @if($user_has_order=="0")
                            <SCRIPT type='text/javascript'>
                                function goChangeBg{{ $i }}(){
                                    if(document.getElementById('chkbox{{ $i }}').checked==false) {

                                        document.getElementById('chkbox{{ $i }}').checked = true;
                                        document.getElementById('span{{ $i }}').classList.remove('btn-danger');
                                        document.getElementById('span{{ $i }}').classList.add('btn-primary');
                                        document.getElementById('span{{ $i }}').innerHTML = '已訂餐';
                                        a= parseInt(document.getElementById('total_days').value);
                                        a+=1;
                                        document.getElementById('total_days').value=a;
                                    }else{

                                        document.getElementById('chkbox{{ $i }}').checked=false;
                                        document.getElementById('span{{ $i }}').classList.remove('btn-primary');
                                        document.getElementById('span{{ $i }}').classList.add('btn-danger');
                                        document.getElementById('span{{ $i }}').innerHTML = '已取消';
                                        a= parseInt(document.getElementById('total_days').value);
                                        a-=1;
                                        document.getElementById('total_days').value=a;
                                    }
                                }
                                function  goChangeBg2{{ $i }}(obj) {
                                    if(obj.checked == false){

                                        document.getElementById('chkbox{{ $i }}').checked=false;
                                        document.getElementById('span{{ $i }}').classList.remove('btn-primary');
                                        document.getElementById('span{{ $i }}').classList.add('btn-danger');
                                        document.getElementById('span{{ $i }}').innerHTML = '已取消';
                                        a= parseInt(document.getElementById('total_days').value);
                                        a-=1;
                                        document.getElementById('total_days').value=a;
                                    }else{

                                        document.getElementById('chkbox{{ $i }}').checked = true;
                                        document.getElementById('span{{ $i }}').classList.remove('btn-danger');
                                        document.getElementById('span{{ $i }}').classList.add('btn-primary');
                                        document.getElementById('span{{ $i }}').innerHTML = '已訂餐';
                                        a= parseInt(document.getElementById('total_days').value);
                                        a+=1;
                                        document.getElementById('total_days').value=a;
                                    }
                                }
                            </SCRIPT>
                            @endif
                            <td class="{{ $bgcolor }}">{{ $v2 }}
                                @if(!empty($order_dates[$v2]))
                                    @if($user_has_order=="0")
                                    <span id="span{{ $i }}" class="btn btn-primary btn-xs" onclick="goChangeBg{{ $i }}();">已訂餐</span><br>
                                    <input type="checkbox" id="chkbox{{ $i }}" name="order_date[{{ $v2 }}]" {{ $checked }} onclick="goChangeBg2{{ $i }}(this);">
                                    @else
                                        @if($tea_dates[$v2] == "eat")
                                            <span id="span{{ $i }}" class="btn btn-primary btn-xs">已訂餐</span><br>
                                            @if($tea_eat_styles[$v2]==1) 葷 <img src="{{ asset('img/meat.png') }}" alt="葷">@endif
                                            @if($tea_eat_styles[$v2]==2) 素 <img src="{{ asset('img/vegetarian.png') }}" alt="素">@endif
                                        @elseif($tea_dates[$v2] == "no_eat")
                                            <span id="span{{ $i }}" class="btn btn-danger btn-xs">已消取</span>
                                        @endif
                                    @endif
                                @else
                                    <span class="btn btn-default btn-xs">不供餐</span>
                                @endif
                            </td>
                            <?php
                            if($w == "0") echo "</tr><tr>";
                            $i++;
                            ?>
                    @endforeach
                        </tr>
                        </tbody>
                    </table>
                @endforeach
                    <input type="hidden" name="semester" value="{{ $semester }}">
                    @if($user_has_order=="0")
                    <button class="btn btn-success">送出</button>
                    @endif
                    {{ Form::close() }}
                    <br>
                    你整學期共訂了 <input type="text" id="total_days" value="{{ $total_order_dates }}" size="2" readonly="readonly"> 天
                @endif
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
@include('layouts.partials.bootbox')