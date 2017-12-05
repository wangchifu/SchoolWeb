@extends('layouts.master-no-hf')

@section('page-title', '午餐系統')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>{{ $semester }} 教職逐日訂餐表</h1>
    </div>
        <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.report_tea1', 'method' => 'POST']) }}
                <input type="hidden" name="semester" value="{{ $semester }}">
                請先選擇餐期：{{ Form::select('order_id', $orders, $this_order_id, ['id' => 'order_id', 'class' => 'form-control', 'placeholder' => '請先選擇餐期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $mon }} 月逐日表</h4>
                </div>
                <div class="panel-content">
                    <table border="1">
                        <thead>
                        <tr>
                            <th>姓名</th>
                            @foreach($order_dates as $order_date)
                            <th>{{ substr($order_date,8,2) }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user_datas as $k1=>$v1)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>{{ $k1 }}</td>
                                @foreach($order_dates as $order_date)
                                    @if($v1[$order_date]['enable'] == "eat")
                                        @if($v1[$order_date]['eat_style'] == "1")
                                            <td><img src="{{ asset('img/meat.png') }}" alt="葷"><br>{{ $v1[$order_date]['place'] }}</td>
                                        @elseif($v1[$order_date]['eat_style'] == "2")
                                            <td><img src="{{ asset('img/vegetarian.png') }}" alt="素"><br>{{ $v1[$order_date]['place'] }}</td>
                                        @endif
                                    @elseif($v1[$order_date]['enable'] == "no_eat")
                                        <td></td>
                                    @endif

                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection