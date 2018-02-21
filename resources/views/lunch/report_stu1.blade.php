@extends('layouts.master-no-hf')

@section('page-title', '午餐系統')

@section('content')
    <style>
        .table-bordered {
            border: 1px solid #ecf0f1 !important;
        }
        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > th,
        .table-bordered > tfoot > tr > th,
        .table-bordered > thead > tr > td,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > td {
            border: 1px solid #000000 !important;
        }
    </style>
<div class="container">
    <div class="page-header">
        <h1>{{ $semester }} 學生身份統計表</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>學生訂餐身份統計表</h4>
                </div>
                <div class="panel-content">
                    {{ Form::open(['route' => 'lunch.report_stu1', 'method' => 'POST']) }}
                    {{ Form::select('select_date', $select_date_menu, $select_date, ['id' => 'select_date', 'class' => 'form-control', 'placeholder' => '請選擇日期','onchange'=>'if(this.value != 0) { this.form.submit(); }']) }}
                    <input type="hidden" name="semester" value="{{ $semester }}">
                    {{ Form::close() }}
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th>班級</th>
                            @if($all_support == '1')
                                <th>訂餐</th>
                            @else
                                <th>一般學生</th><th>經濟弱勢學生</th>
                            @endif
                            <th>不訂餐</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $a=0;$ab=0;$ag=0;$g=0;$w=0;$n=0;$gb=0;$gg=0;$wb=0;$wg=0;$nb=0;$ng=0; ?>
                        @foreach($order_data as $k=>$v)
                            <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                                <td>
                                    {{ $k }}
                                </td>
                                @if($all_support == '1')
                                <td><?php
                                    if(empty($v[$select_date]['a'])) $v[$select_date]['a'] = 0;
                                    if(empty($v[$select_date]['ab'])) $v[$select_date]['ab'] = 0;
                                    if(empty($v[$select_date]['ag'])) $v[$select_date]['ag'] = 0;
                                    ?>
                                    {{ $v[$select_date]['a'] }} (男：{{ $v[$select_date]['ab'] }} , 女：{{ $v[$select_date]['ag'] }})
                                    <?php
                                    $a+=$v[$select_date]['a'];
                                    $ab+=$v[$select_date]['ab'];
                                    $ag+=$v[$select_date]['ag'];
                                    ?>
                                </td>
                                @else
                                <td><?php
                                    if(empty($v[$select_date]['g'])) $v[$select_date]['g'] = 0;
                                    if(empty($v[$select_date]['gb'])) $v[$select_date]['gb'] = 0;
                                    if(empty($v[$select_date]['gg'])) $v[$select_date]['gg'] = 0;
                                    ?>
                                    {{ $v[$select_date]['g'] }} (男：{{ $v[$select_date]['gb'] }} , 女：{{ $v[$select_date]['gg'] }})
                                    <?php
                                    $g+=$v[$select_date]['g'];
                                    $gb+=$v[$select_date]['gb'];
                                    $gg+=$v[$select_date]['gg'];
                                    ?>
                                </td>
                                <td><?php
                                    if(empty($v[$select_date]['w'])) $v[$select_date]['w'] = 0;
                                    if(empty($v[$select_date]['wb'])) $v[$select_date]['wb'] = 0;
                                    if(empty($v[$select_date]['wg'])) $v[$select_date]['wg'] = 0;
                                    ?>
                                    {{ $v[$select_date]['w'] }} (男：{{ $v[$select_date]['wb'] }} , 女：{{ $v[$select_date]['wg'] }})
                                    <?php
                                    $w+=$v[$select_date]['w'];
                                    $wb+=$v[$select_date]['wb'];
                                    $wg+=$v[$select_date]['wg'];
                                    ?>
                                </td>
                                @endif
                                <td><?php
                                    if(empty($v[$select_date]['n'])) $v[$select_date]['n'] = 0;
                                    if(empty($v[$select_date]['nb'])) $v[$select_date]['nb'] = 0;
                                    if(empty($v[$select_date]['ng'])) $v[$select_date]['ng'] = 0;
                                    ?>
                                    {{ $v[$select_date]['n'] }} (男：{{ $v[$select_date]['nb'] }} , 女：{{ $v[$select_date]['ng'] }})
                                    <?php
                                    $n+=$v[$select_date]['n'];
                                    $nb+=$v[$select_date]['nb'];
                                    $ng+=$v[$select_date]['ng'];
                                    ?>
                                </td>
                            </tr>
                        @endforeach
                        <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                            <td>合計</td>
                            @if($all_support == '1')
                            <td>{{ $a }} (男：{{ $ab }} , 女：{{ $ag }})</td>
                            @else
                            <td>{{ $g }} (男：{{ $gb }} , 女：{{ $gg }})</td><td>{{ $w }} (男：{{ $wb }} , 女：{{ $wg }})</td>
                            @endif
                            <td>{{ $n }} (男：{{ $nb }} , 女：{{ $ng }})</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-heading">
                    <h4>經濟弱勢學生身份分類表</h4>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr class="bg-primary">
                        <th rowspan="2">班級</th><th rowspan="2">低收入戶</th><th rowspan="2">中低收入戶</th><th rowspan="2">家庭突發因素</th><th colspan="7">經導師家庭訪視認定</th>
                    </tr>
                    <tr class="bg-info"><th>父母一方失業</th><th>單親家庭</th><th>隔代教養</th><th>特殊境遇</th><th>身心障礙學生</th><th>新住民子女</th><th>原住民子女</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $w201 =0;
                    $w202 =0;
                    $w203 =0;
                    $w204 =0;
                    $w205 =0;
                    $w206 =0;
                    $w207 =0;
                    $w208 =0;
                    $w209 =0;
                    $w210 =0;
                    $w201b =0;
                    $w201g =0;
                    $w202b =0;
                    $w202g =0;
                    $w203b =0;
                    $w203g =0;
                    $w204b =0;
                    $w204g =0;
                    $w205b =0;
                    $w205g =0;
                    $w206b =0;
                    $w206g =0;
                    $w207b =0;
                    $w207g =0;
                    $w208b =0;
                    $w208g =0;
                    $w209b =0;
                    $w209g =0;
                    $w210b =0;
                    $w210g =0;
                    ?>
                    @foreach($order_data as $k=>$v)
                        <?php
                        if(empty($v[$select_date]['w201'])) $v[$select_date]['w201'] = 0;
                        if(empty($v[$select_date]['w202'])) $v[$select_date]['w202'] = 0;
                        if(empty($v[$select_date]['w203'])) $v[$select_date]['w203'] = 0;
                        if(empty($v[$select_date]['w204'])) $v[$select_date]['w204'] = 0;
                        if(empty($v[$select_date]['w205'])) $v[$select_date]['w205'] = 0;
                        if(empty($v[$select_date]['w206'])) $v[$select_date]['w206'] = 0;
                        if(empty($v[$select_date]['w207'])) $v[$select_date]['w207'] = 0;
                        if(empty($v[$select_date]['w208'])) $v[$select_date]['w208'] = 0;
                        if(empty($v[$select_date]['w209'])) $v[$select_date]['w209'] = 0;
                        if(empty($v[$select_date]['w210'])) $v[$select_date]['w210'] = 0;
                        if(empty($v[$select_date]['w201b'])) $v[$select_date]['w201b'] = 0;
                        if(empty($v[$select_date]['w201g'])) $v[$select_date]['w201g'] = 0;
                        if(empty($v[$select_date]['w202b'])) $v[$select_date]['w202b'] = 0;
                        if(empty($v[$select_date]['w202g'])) $v[$select_date]['w202g'] = 0;
                        if(empty($v[$select_date]['w203b'])) $v[$select_date]['w203b'] = 0;
                        if(empty($v[$select_date]['w203g'])) $v[$select_date]['w203g'] = 0;
                        if(empty($v[$select_date]['w204b'])) $v[$select_date]['w204b'] = 0;
                        if(empty($v[$select_date]['w204g'])) $v[$select_date]['w204g'] = 0;
                        if(empty($v[$select_date]['w205b'])) $v[$select_date]['w205b'] = 0;
                        if(empty($v[$select_date]['w205g'])) $v[$select_date]['w205g'] = 0;
                        if(empty($v[$select_date]['w206b'])) $v[$select_date]['w206b'] = 0;
                        if(empty($v[$select_date]['w206g'])) $v[$select_date]['w206g'] = 0;
                        if(empty($v[$select_date]['w207b'])) $v[$select_date]['w207b'] = 0;
                        if(empty($v[$select_date]['w207g'])) $v[$select_date]['w207g'] = 0;
                        if(empty($v[$select_date]['w208b'])) $v[$select_date]['w208b'] = 0;
                        if(empty($v[$select_date]['w208g'])) $v[$select_date]['w208g'] = 0;
                        if(empty($v[$select_date]['w209b'])) $v[$select_date]['w209b'] = 0;
                        if(empty($v[$select_date]['w209g'])) $v[$select_date]['w209g'] = 0;
                        if(empty($v[$select_date]['w210b'])) $v[$select_date]['w210b'] = 0;
                        if(empty($v[$select_date]['w210g'])) $v[$select_date]['w210g'] = 0;
                        ?>
                    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                        <td>
                            {{ $k }}
                        </td>
                        <td>
                            {{ $v[$select_date]['w201'] }}
                            <?php
                            $w201+=$v[$select_date]['w201'];
                            $w201b+=$v[$select_date]['w201b'];
                            $w201g+=$v[$select_date]['w201g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w202'] }}
                            <?php
                            $w202+=$v[$select_date]['w202'];
                            $w202b+=$v[$select_date]['w202b'];
                            $w202g+=$v[$select_date]['w202g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w203'] }}
                            <?php
                            $w203+=$v[$select_date]['w203'];
                            $w203b+=$v[$select_date]['w203b'];
                            $w203g+=$v[$select_date]['w203g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w204'] }}
                            <?php
                            $w204+=$v[$select_date]['w204'];
                            $w204b+=$v[$select_date]['w204b'];
                            $w204g+=$v[$select_date]['w204g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w205'] }}
                            <?php
                            $w205+=$v[$select_date]['w205'];
                            $w205b+=$v[$select_date]['w205b'];
                            $w205g+=$v[$select_date]['w205g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w206'] }}
                            <?php
                            $w206+=$v[$select_date]['w206'];
                            $w206b+=$v[$select_date]['w206b'];
                            $w206g+=$v[$select_date]['w206g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w207'] }}
                            <?php
                            $w207+=$v[$select_date]['w207'];
                            $w207b+=$v[$select_date]['w207b'];
                            $w207g+=$v[$select_date]['w207g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w208'] }}
                            <?php
                            $w208+=$v[$select_date]['w208'];
                            $w208b+=$v[$select_date]['w208b'];
                            $w208g+=$v[$select_date]['w208g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w209'] }}
                            <?php
                            $w209+=$v[$select_date]['w209'];
                            $w209b+=$v[$select_date]['w209b'];
                            $w209g+=$v[$select_date]['w209g'];
                            ?>
                        </td>
                        <td>
                            {{ $v[$select_date]['w210'] }}
                            <?php
                            $w210+=$v[$select_date]['w210'];
                            $w210b+=$v[$select_date]['w210b'];
                            $w210g+=$v[$select_date]['w210g'];
                            ?>
                        </td>
                    </tr>
                    @endforeach
                    <tr bgcolor='#FFFFFF' onmouseover="this.style.backgroundColor='#FFCDE5';" onMouseOut="this.style.backgroundColor='#FFFFFF';">
                        <td>合計</td><td>{{ $w201 }} (男{{ $w201b }} , 女{{ $w201g }})</td><td>{{ $w202 }} (男{{ $w202b }} , 女{{ $w202g }})</td><td>{{ $w203 }} (男{{ $w203b }} , 女{{ $w203g }})</td><td>{{ $w204 }} (男{{ $w204b }} , 女{{ $w204g }})</td><td>{{ $w205 }} (男{{ $w205b }} , 女{{ $w205g }})</td><td>{{ $w206 }} (男{{ $w206b }} , 女{{ $w206g }})</td><td>{{ $w207 }} (男{{ $w207b }} , 女{{ $w207g }})</td><td>{{ $w208 }} (男{{ $w208b }} , 女{{ $w208g }})</td><td>{{ $w209 }} (男{{ $w209b }} , 女{{ $w209g }})</td><td>{{ $w210 }} (男{{ $w210b }} , 女{{ $w210g }})</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection