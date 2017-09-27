@extends('layouts.master')

@section('page-title', '教室預約-管理')

@section('content')
    <div class="page-header">
        <h1>教室預約-管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('classrooms.index',date('Y-m-d')) }}">預約</a></li>
        <li class="active"><a href="{{ route('classrooms.admin') }}">管理</a></li>
    </ul>
    <br><br>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>新增教室</h4>
                </div>
                <div class="panel-content">
                        {{ Form::open(['route' => 'classrooms.addClassroom', 'method' => 'POST']) }}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>啟用？</th>
                            <th>教室名稱</th>
                            <th>開放節次</th>
                            <th>不開放星期及節次</th>
                            <td>動作</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><input name="active" type="checkbox" value="1" class="form-control" style="zoom:80%" checked="checked"></td>
                            <td class="col-md-2">{{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '教室名稱','required'=>'required']) }}</td>
                            <td class="col-md-3">{{ Form::text('openSections', '早修,1,2,3,4,午休,5,6,7,8', ['id' => 'closeSections', 'class' => 'form-control', 'placeholder' => '開放節次','required'=>'required']) }}</td>
                            <td class="col-md-6">{{ Form::text('closeSections', '6早修,61,62,63,64,6午休,65,66,67,68,0早修,01,02,03,04,0午休,05,06,07,08', ['id' => 'openSections', 'class' => 'form-control', 'placeholder' => '不開放星期節次']) }}</td>
                            <td><button class="btn btn-success">新增</button></td>
                        </tr>
                        </tbody>
                    </table>
                        {{ Form::close() }}
                    註：不開放星期及節次，03，代表星期日第三節；請用逗號：「 , 」隔開


                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>已開放教室管理</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>啟用？</th><th>教室名稱</th><th>開放節次</th><th>不開放星期及節次</th><th>動作</th></tr>
                        </thead>
                        <tbody>
                        @foreach($classrooms as $classroom)
                            <?php
                            if($classroom->active=="1"){
                                $checked="checked=checked";
                            }else{
                                $checked="";
                            }
                            ?>
                            {{ Form::open(['route' => ['classrooms.updateClassroom',$classroom->id],'id'=>'form'.$classroom->id ,'onsubmit'=>'return false;','method' => 'PATCH']) }}
                            <tr><td><input name="active" type="checkbox" value="1" class="form-control" style="zoom:80%" {{ $checked }}></td>
                                <td class="col-md-1">{{ Form::text('name', $classroom->name, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '教室名稱','required'=>'required']) }}</td>
                                <td class="col-md-3">{{ Form::text('openSections', $classroom->openSections, ['id' => 'openSections', 'class' => 'form-control', 'placeholder' => '開放節次','required'=>'required']) }}</td>
                                <td class="col-md-6">{{ Form::text('closeSections', $classroom->closeSections, ['id' => 'closeSections', 'class' => 'form-control', 'placeholder' => '不開放星期節次']) }}</td>
                                <td class="col-md-1"><button class="btn btn-info btn-xs" onclick="bbconfirm('form{{$classroom->id}}','你要修改教室 {{ $classroom->name }} ?')">修改</button> <a href="{{ route('classrooms.delClassroom',$classroom->id) }}" id="link{{ $classroom->id }}" class="btn btn-danger btn-xs" onclick="bbconfirm2('link{{ $classroom->id }}','你要刪除教室 {{ $classroom->name }}')">刪除</a></td>
                            </tr>
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@include('layouts.partials.bootbox')