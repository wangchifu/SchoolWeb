@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1>教職員訂餐</h1>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="">2.學生訂餐</a></li>
        <li><a href="">3.供餐確認表</a></li>
        <li><a href="">4.滿意度調查</a></li>
        <li><a href="">5.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">6.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <h4>選擇學期</h4>
                {{ Form::open(['route' => 'lunch.index', 'method' => 'POST']) }}
                {{ Form::select('semester', $semesters, $semester, ['id' => 'semester', 'class' => 'form-control', 'placeholder' => '請選擇學期','onchange'=>'this.form.submit();']) }}
                {{ Form::close() }}
            </div>
            @if(!empty($semester))
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>請選擇你 {{ $semester }} 訂餐日期後最下方送出</h3>
                </div>
                <div class="panel-content">
                <?php
                    $i=1;
                    $total_days = count($order_dates);
                ?>
                {{ Form::open(['route'=>'lunch.store_tea_date','method'=>'POST']) }}
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
                            if(!empty($tea_dates[$v2])){
                                $checked = "checked";
                            }else{
                                $checked = "";
                            }
                            ?>
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
                            <td class="{{ $bgcolor }}">{{ $v2 }}
                                @if(!empty($order_dates[$v2]))
                                    <span id="span{{ $i }}" class="btn btn-primary btn-xs" onclick="goChangeBg{{ $i }}();">已訂餐</span>
                                    <input type="checkbox" id="chkbox{{ $i }}" name="order_date[{{ $v2 }}]" {{ $checked }} onclick="goChangeBg2{{ $i }}(this);">
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
                    <button class="btn btn-success">送出</button>
                    {{ Form::close() }}
                    <br>
                    你整學期共訂了 <input type="text" id="total_days" value="{{ $total_days }}" size="2" readonly="readonly"> 天
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection