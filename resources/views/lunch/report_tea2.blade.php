@extends('layouts.master-no-hf')

@section('page-title', '午餐系統')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>{{ $semester }} 教職學期收費表</h1>
    </div>
        <div class="row">
            {{ Form::open(['route'=>'lunch.report_tea2_print','method'=>'POST','target'=>'_blank']) }}
            <input type="hidden" name="semester" value="{{ $semester }}">
            <button class="btn btn-success">列印收費單據</button>
            {{ Form::close() }}
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>收費表</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>姓名</th><th>訂餐日數</th><th>收費</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i =0;$num=1;?>
                            @foreach($user_datas as $k => $v)
                                <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                    <td>
                                        {{ $num }}-{{ $k }}
                                    </td>
                                    <td>
                                        {{ $v }}
                                    </td>
                                    <td>
                                        {{ $v*$tea_money }}
                                    </td>
                                </tr>
                                <?php $i+=$v*$tea_money; $num++;?>
                            @endforeach
                                <tr>
                                    <td>
                                        合計
                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        {{ $i }}
                                    </td>

                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection