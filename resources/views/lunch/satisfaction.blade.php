@extends('layouts.master')

@section('page-title', '午餐系統')

@section('content')
    <div class="page-header">
        <h1><img src="{{ asset('/img/lunch/smile.png') }}" alt="滿意度調查" width="50">滿意度調查</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('lunch.index') }}">1.教職員訂餐</a></li>
        <li><a href="{{ route('lunch.stu') }}">2.學生訂餐</a></li>
        <li><a href="{{ route('lunch.stu_cancel') }}">3.學生退餐</a></li>
        <li><a href="{{ route('lunch.check') }}">4.供餐問題</a></li>
        <li class="active"><a href="{{ route('lunch.satisfaction') }}">5.滿意度調查</a></li>
        <li><a href="{{ route('lunch.special') }}">6.特殊處理</a></li>
        <li><a href="{{ route('lunch.report') }}">7.報表輸出</a></li>
        <li><a href="{{ route('lunch.setup') }}">8.系統管理</a></li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                {{ Form::open(['route' => 'lunch.satisfaction', 'method' => 'POST']) }}
                請先選擇學期：{{ Form::select('semester', $semesters, $semester, ['id' => 'semester', 'class' => 'form-control', 'placeholder' => '請先選擇學期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                {{ Form::close() }}
            </div>
            <div class="panel panel-default">
                @if(empty($is_admin))
                    <div class="panel-heading">
                        <h4>班級：{{ $class_id }}</h4>
                    </div>
                    <div class="panel-content">
                        <table class="table table-bordered">
                            <tr class="bg-primary">
                                <td>
                                    學期
                                </td>
                                <td>
                                    調查表
                                </td>
                                <td>
                                    狀況
                                </td>
                            </tr>
                        @foreach($satisfactions as $satisfaction)
                            @if($satisfaction->semester == $semester)
                                <?php
                                    $has_done = \App\LunchSatisfactionClass::where('lunch_satisfaction_id','=',$satisfaction->id)
                                        ->where('class_id','=',$class_id)
                                        ->count();
                                ?>
                                <tr>
                                    <td>
                                        {{ $satisfaction->semester }}
                                    </td>
                                    <td>
                                        {{ $satisfaction->name }}
                                    </td>
                                    <td>
                                        @if($has_done == 0)
                                        <a href="{{ route('lunch.satisfaction_show',$satisfaction->id) }}" class="btn btn-success" target="_blank">填寫</a>
                                        @else
                                            <a href="{{ route('lunch.satisfaction_show_answer',$satisfaction->id) }}" class="btn btn-danger" target="_blank">已填寫</a>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </table>
                    </div>
                @endif
            </div>
            @if($is_admin)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>管理調查表</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-bordered">
                        <tr class="bg-primary">
                            <th class="col-md-1">
                                學期
                            </th>
                            <th>
                                調查表名稱
                            </th>
                            <th>
                                回報班級數
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        {{ Form::open(['route'=>'lunch.satisfaction_store','method'=>'POST','id'=>'satisfaction_store','onsubmit'=>'return false']) }}
                        <tr class="bg-success">
                            <td>
                                {{ Form::text('semester',$semester,['id'=>'semester','class' => 'form-control', 'placeholder' => '1061','required'=>'required']) }}
                            </td>
                            <td>
                                {{ Form::text('name',$semester.'第1次滿意度調查表',['id'=>'name','class' => 'form-control', 'placeholder' => '106第1次滿意度調查表','required'=>'required']) }}
                            </td>
                            <td>

                            </td>
                            <td>
                                <a href="#" class="btn btn-success" onclick="bbconfirm('satisfaction_store','確定要新增調查表？')">新增</a>
                            </td>
                        </tr>
                        {{ Form::close() }}
                        @foreach($satisfactions as $satisfaction)
                            {{ Form::open(['route'=>['lunch.satisfaction_destroy',$satisfaction->id],'method'=>'POST','id'=>'satisfaction_destroy','onsubmit'=>'return false']) }}
                        <tr>
                            <td>
                                {{ $satisfaction->semester }}
                            </td>
                            <td>
                                {{ $satisfaction->name }}
                            </td>
                            <?php
                                $classes_data = \App\LunchSatisfactionClass::where('lunch_satisfaction_id','=',$satisfaction->id)->get();
                                $class_name = "已交：";
                                $num = 0;
                                foreach($classes_data as $class_data){
                                    $class_name .= $class_data->class_id." , ";
                                    $num++;
                                }
                            ?>
                            <td>
                                <a href="#" class="btn btn-warning" id="a{{ $satisfaction->id }}">{{ $num }}</a>
                            </td>
                            <td>
                                <a href="{{ route('lunch.satisfaction_help',$satisfaction->id) }}" class="btn btn-primary" id="help{{ $satisfaction->id }}" onclick="bbconfirm2('help{{ $satisfaction->id }}','確定要填滿這個調查表？')">一鍵幫填</a>
                                <a href="{{ route('lunch.satisfaction_print',$satisfaction->id) }}" class="btn btn-success" target="_blank">列印調查表</a>
                                <a href="#" class="btn btn-danger" onclick="bbconfirm('satisfaction_destroy','確定要刪除這個調查表？')">刪除</a>
                            </td>
                        </tr>
                            <script>
                                $('#a{{ $satisfaction->id }}').click(function(){
                                    $('#td{{ $satisfaction->id }}').toggle();
                                });
                            </script>
                        <tr id="td{{ $satisfaction->id }}" style="display:none">
                            <td></td>
                            <td colspan="2">
                                {{ $class_name }}
                            </td>
                            <td></td>
                        </tr>
                            {{ Form::close() }}
                        @endforeach
                    </table>
                </div>
            </div>
            @endif


        </div>

@endsection
@include('layouts.partials.bootbox')