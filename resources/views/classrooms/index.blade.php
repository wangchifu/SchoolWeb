@extends('layouts.master')

@section('page-title', '教室預約')

@section('content')
    <div class="page-header">
        <h1>教室預約</h1>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{ route('classrooms.index',date('Y-m-d')) }}">預約</a></li>
        @can('create', App\Classroom::class)
            <li><a href="{{ route('classrooms.admin') }}">管理</a></li>
        @endcan
    </ul>
    <br><br>
    <div class="row">
        <div class="col-md-8">
            {{ Form::open(['route' => 'classrooms.index', 'method' => 'GET']) }}
            <table class="table table-striped">
                <tr>
                    <td>
                        <label for="classroom_id">選擇教室：</label></td>
                    <td>
                        {{ Form::select('classroom_id', $classrooms_menu, null, ['id' => 'classroom_id', 'class' => 'form-control', 'placeholder' => '請選擇教室','required'=>'required']) }}
                    </td>
                    <td>
                        <label for="this_date">選擇日期：</label>
                    </td>
                    <td>
                        <script src="{{ asset('js/cal/jscal2.js') }}"></script>
                        <script src="{{ asset('js/cal/lang/cn.js') }}"></script>
                        <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/jscal2.css') }}">
                        <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/border-radius.css') }}">
                        <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/steel/steel.css') }}">
                        <input id="this_date" name="this_date" value="{{ substr($dates['d0'],0,10) }}" class="form-control">
                        <script>
                            Calendar.setup({
                                dateFormat : '%Y-%m-%d',
                                inputField : 'this_date',
                                trigger    : 'this_date',
                                onSelect   : function() { this.hide();}
                            });
                        </script>
                    </td>
                    <td>
                        <button class="btn btn-success btn">送出</button>
                    </td>
                </tr>
            </table>
            {{ Form::close() }}
            @if($classroom != "")
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>預約教室</h4>
                    </div>
                    <div class="panel-content">
                        <table class="table table-striped">
                            <thead><tr><th>日期</th>
                                <?php
                                    $d = ['0'=>'星期日','1'=>'星期一','2'=>'星期二','3'=>'星期三','4'=>'星期四','5'=>'星期五','6'=>'星期六'];
                                ?>
                                @foreach($dates as $date)
                                    <?php
                                        $this_day = explode("-",$date);
                                    ?>
                                    <th>{{ $this_day[1] }}-{{ $this_day[2] }}</th>
                                @endforeach
                            </tr><tr><th>節次</th>
                                @foreach($dates as $date)
                                    <?php
                                    $this_day = explode("-",$date);
                                    ?>
                                    <th>{{ $d[$this_day[3]] }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $openSections = explode(',',$classroom->openSections);
                                $closeSections = explode(',',$classroom->closeSections);
                                $cols=array('早修',1,2,3,4,'午休',5,6,7,8);
                            ?>
                            @foreach($cols as $k1=>$v1)
                                <tr>
                                    <th>{{ $v1 }}</th>
                                @foreach($dates as $date)
                                    <?php
                                        $date2 = substr($date,0,10);
                                        $hasOrder = App\OrderClassroom::where('classroom_id','=',$classroom->id)->where('orderDate','=',$date2)->where('section','=',$v1)->first();
                                    ?>
                                        <td>
                                            @if(in_array($v1,$openSections))
                                                <?php $ws = date("w",strtotime($date2)) . $v1; ?>
                                                @if(in_array($ws,$closeSections))
                                                    -
                                                @else
                                                    @if($hasOrder)
                                                        <small class="text-primary">({{ $hasOrder->user->name }})</small>
                                                        @if(auth()->user()->id == $hasOrder->user_id)
                                                            <br>
                                                            <a href="{{ route('classrooms.delOrder',$hasOrder->id) }}" id="OC{{ $hasOrder->id }}" class="btn btn-danger btn-xs" onclick="bbconfirm2('OC{{ $hasOrder->id }}','確定刪除 {{ $classroom->name }}<br>{{ $date2 }} 節次 {{ $v1 }} 的預約？')">刪</a></form>
                                                        @endif
                                                    @else
                                                        {{ Form::open(['route' => 'classrooms.storeOrder','id'=>'form'.$date2.$v1,'onsubmit' => 'return false;', 'method' => 'POST']) }}
                                                        <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                                                        <input type="hidden" name="orderDate" value="{{ $date2 }}">
                                                        <input type="hidden" name="section" value="{{ $v1 }}">
                                                        <button class="btn btn-success btn-xs" onclick="bbconfirm('form{{$date2.$v1}}','你預約的是：{{ $classroom->name."<br>".$date2." 節次：".$v1 }}');">預</button>
                                                        {{ Form::close() }}
                                                    @endif
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
        </div>
    </div>
@endsection

@include('layouts.partials.bootbox')