@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1><img src="{{ asset('/img/lunch/student_cancel.png') }}" alt="學生退餐" width="50">學生退餐</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li class="active"><a href="{{ route('lunch.stu_cancel') }}">3.學生退餐</a></li>
        <li><a href="">4.供餐確認表</a></li>
        <li><a href="">5.滿意度調查</a></li>
        <li><a href="{{ route('lunch.special') }}">6.特殊處理</a></li>
        <li><a href="{{ route('lunch.report') }}">7.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">8.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>{{ $semester }} 學期 {{ $is_tea }}</h3>
                    @if($is_admin==1)
                    {{ Form::open(['route' => 'lunch.stu_cancel', 'method' => 'POST']) }}
                    <input type="text" name="select_class" value="{{ $class_id }}" placeholder="3碼班級代號" maxlength="3"><button class="btn btn-success btn-xs">送出</button>
                    {{ Form::close() }}
                    @endif
                </div>

                    {{ Form::open(['route' => 'lunch.stu_cancel', 'method' => 'POST']) }}
                    <h3>1.請選擇餐期：</h3>{{ Form::select('select_order_id', $lunch_orders, $lunch_order_id, ['id' => 'select_order_id', 'class' => 'form-control', 'placeholder' => '請選擇餐期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                    <input type="hidden" name="select_class" value="{{ $class_id }}">
                    {{ Form::close() }}

                <div class="panel-content">
                    <h3>2.請點下列圖示退餐：</h3>
                    <table class="table table-striped">

                        <tbody>
                        @if(!empty($has_order))
                            <?php $i = 0; ?>
                            @foreach($stu_data as $k1=>$v1)
                                @if($i%10 == 0)
                                    <tr>
                                        <th rowspan="2">座號</th><th rowspan="2">姓名</th><th colspan="{{ count($this_order_dates) }}">{{ $lunch_orders[$lunch_order_id] }}月 日期</th>
                                    </tr>
                                    <tr>
                                        @foreach($this_order_dates as $k=>$v)
                                            <th>{{ substr($v,8,2) }}</th>
                                        @endforeach
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        {{ $k1 }}</td><td>{{ $v1['name'] }}
                                        @if($v1['out_in'] == "in")
                                            <button class="btn btn-info btn-xs">轉入生</button>
                                        @endif
                                        @if($v1['out_in'] == "out")
                                            <button class="btn btn-danger btn-xs">轉出生 </button>
                                        @endif
                                    </td>
                                    @foreach($this_order_dates as $k2=>$v2)
                                        <?php
                                        $cancel_data['do'] = "1";
                                        $cancel_data['semester'] = $semester;
                                        $cancel_data['student_id'] = $v1['id'];
                                        $cancel_data['class_id'] = $class_id;
                                        $cancel_data['order_date'] = $v2;
                                        $cancel_data['lunch_order_id'] = $lunch_order_id;
                                        $name = $v1['name'];

                                        if($order_data[$v1['id']][$v2]['p_id'] > 200 and $order_data[$v1['id']][$v2]['p_id'] < 300){
                                            $w_color = "#FFFFBB";
                                        }else{
                                            $w_color = "";
                                        }
                                        ?>
                                        @if($order_data[$v1['id']][$v2]['eat_style'] != 3)
                                            <?php $cancel_data['enable'] = "abs"; ?>
                                            @if($order_data[$v1['id']][$v2]['enable'] == "eat" and $order_data[$v1['id']][$v2]['eat_style'] == 1)
                                                <td bgcolor="{{ $w_color }}"><a href="{{ route('lunch.stu_cancel',$cancel_data) }}" onclick="return confirm('是否取消 {{ $class_id }}班 {{ $name }} {{ $v2 }} 的訂餐？');"><img src="{{ asset('/img/meat.png') }}" alt="葷"></a></td>
                                            @elseif($order_data[$v1['id']][$v2]['enable'] == "eat" and $order_data[$v1['id']][$v2]['eat_style'] == 2)
                                                <td bgcolor="{{ $w_color }}"><a href="{{ route('lunch.stu_cancel',$cancel_data) }}" onclick="return confirm('是否取消 {{ $class_id }}班 {{ $name }} {{ $v2 }} 的訂餐？');"><img src="{{ asset('/img/vegetarian.png') }}" alt="素"></a></td>
                                            @elseif($order_data[$v1['id']][$v2]['enable'] == "abs")
                                            <?php $cancel_data['enable'] = "eat"; ?>
                                                    <td bgcolor="{{ $w_color }}"><a href="{{ route('lunch.stu_cancel',$cancel_data) }}" onclick="return confirm('是否恢復 {{ $class_id }}班 {{ $name }} {{ $v2 }} 的訂餐？');"><img src="{{ asset('/img/no_check.png') }}" alt="退餐"></a></td>
                                            @elseif($order_data[$v1['id']][$v2]['enable'] == "out")
                                                <td bgcolor="{{ $w_color }}"><img src="{{ asset('/img/had_back.png') }}" alt="已退費"></td>
                                            @elseif($order_data[$v1['id']][$v2]['enable'] == "not")
                                                <td bgcolor="{{ $w_color }}"><img src="{{ asset('/img/remove.png') }}" alt="當日未供餐"></td>
                                            @elseif($order_data[$v1['id']][$v2]['enable'] == "no_eat")
                                                <td bgcolor="{{ $w_color }}"><img src="{{ asset('/img/minus.png') }}" alt="沒有訂餐"></td>
                                            @endif
                                        @elseif($order_data[$v1['id']][$v2]['eat_style'] == 3)
                                            @if($order_data[$v1['id']][$v2]['enable'] == "no_eat")
                                            <?php $stu_name= $v1['name']; ?>
                                                <td bgcolor="{{ $w_color }}"><a href="#" onclick="alert('{{ $stu_name }} 沒有訂餐！');"><img src="{{ asset('/img/minus.png') }}" alt="未訂餐"></a></td>
                                            @elseif($order_data[$v1['id']][$v2]['enable'] == "out")
                                                <td bgcolor="{{ $w_color }}"><img src="{{ asset('/img/had_back.png') }}" alt="已退費"></td>
                                            @endif
                                        @endif
                                    @endforeach
                                </tr>
                                <?php $i++;?>
                            @endforeach
                        @else
                            <h3 class="text-danger">該班尚未訂餐</h3>
                        @endif
                        </tbody>
                    </table>
                    圖例：<br>
                    <img src="{{ asset('/img/minus.png') }}" alt="未訂餐"> ：學生未訂餐<br>
                    <img src="{{ asset('/img/meat.png') }}" alt="葷"> ：訂葷食<br>
                    <img src="{{ asset('/img/vegetarian.png') }}" alt="素"> ：訂素食<br>
                    <img src="{{ asset('/img/no_check.png') }}" alt="退餐"> ：該日退餐，再按一下可以再訂餐<br>
                    <img src="{{ asset('/img/had_back.png') }}" alt="已退費"> ：該日為轉出生先行退費了！<br>
                    <span style="background-color: #FFFFBB;">　　　　　　</span> ：底色為淺黃色的，是弱勢生。<br>

                </div>
            </div>
        </div>
    </div>
@endsection
@include('layouts.partials.bootbox')