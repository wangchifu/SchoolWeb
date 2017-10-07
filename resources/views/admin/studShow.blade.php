@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin') }}">學生管理</a></li>
        <li class="active"><a href="{{ url('admin/studAdmin') }}">學生管理</a></li>
        <li><a href="{{ url('admin/funAdmin') }}">指定管理</a></li>
        <li><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br>
    {{ Form::open(['route' => 'admin.indexStud', 'method' => 'POST']) }}
    <button class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> 返回</button></a>
    <input type="hidden" name="semester" value="{{$yearClass->semester}}">
    {{ Form::close() }}
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>班級管理：{{ $yearClass->semester }} 學期 {{ $yearClass->name }}</h4>
                </div>
                <div class="panel-body forum-content">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                                班級代號
                            </th>
                            <th>
                                座號
                            </th>
                            <th>
                                學號
                            </th>
                            <th>
                                姓名
                            </th>
                            <th>
                                性別
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            {{ Form::open(['route' => ['admin.addStud',$yearClass->id], 'method' => 'POST','id' => 'addStud','onsubmit'=>'return false;']) }}
                            <td class="col-md-2">
                                {{ Form::text('year_class',$yearClass->year_class, ['id' => 'year_class', 'class' => 'form-control', 'readonly' => 'readonly']) }}
                                <input type="hidden" name="year_class_id" value="{{ $yearClass->id }}">
                            </td>
                            <td class="col-md-2">
                                {{ Form::text('num',null, ['id' => 'num', 'class' => 'form-control', 'placeholder' => '座號2碼']) }}
                            </td>
                            <td class="col-md-2">
                                {{ Form::text('sn',null, ['id' => 'sn', 'class' => 'form-control', 'placeholder' => '學號6碼']) }}
                            </td>
                            <td class="col-md-2">
                                {{ Form::text('name',null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '學生姓名']) }}
                            </td>
                            <td class="col-md-2">
                                <?php $stud_sex = [1=>'男',2=>'女']; ?>
                                {{ Form::select('sex', $stud_sex, 1, ['id' => 'sex', 'class' => 'form-control', 'placeholder' => '選擇','required'=>'required']) }}
                            </td>
                            <td class="col-md-3">
                                <button class="btn btn-success btn-xs" onclick="bbconfirm('addStud','確定要新增嗎？')">新增學生</button>
                            </td>
                            {{ Form::close() }}
                        </tr>
                        @foreach($student_data as $k=>$v)
                            {{ Form::open(['route' => ['admin.updateStud',$yearClass->id], 'method' => 'POST','id' => 'year_class'.$k,'onsubmit'=>'return false;']) }}
                            <input type="hidden" name="semester" value="{{ $yearClass->semester }}">
                            <input type="hidden" name="id" value="{{ $v['id'] }}">
                            <tr>
                                <td>
                                    {{ Form::text('year_class',$v['班級'], ['class' => 'form-control', 'placeholder' => '請輸入班級代號']) }}
                                </td>
                                <td>
                                    {{ Form::text('num',$k, ['id' => 'num', 'class' => 'form-control', 'placeholder' => '請輸入座號']) }}
                                </td>
                                <td>
                                    {{ Form::text('sn',$v['學號'], ['id' => 'sn', 'class' => 'form-control', 'readonly' => 'readonly']) }}
                                </td>
                                <td>
                                    {{ Form::text('name',$v['姓名'], ['id' => 'name', 'class' => 'form-control', 'placeholder' => '請輸入學生姓名']) }}
                                </td>
                                <td>
                                    {{ Form::select('sex', $stud_sex, $v['性別'], ['id' => 'sex', 'class' => 'form-control', 'placeholder' => '選擇','required'=>'required']) }}
                                </td>
                                <td>
                                    <button class="btn btn-info btn-xs" onclick="bbconfirm('year_class{{ $k }}','你真的要修改嗎？')">修改</button> <a href="{{ route('admin.outStud',$v['id']) }}" class="btn btn-danger btn-xs" id="out" onclick="bbconfirm2('out','確定要轉出？')">轉出</a>
                                </td>
                            </tr>
                            {{ Form::close() }}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well">
                導師：
                @if($yearClass->user_id)
                    {{ $yearClass->user->name}}
                @endif
                <br>
                <label for="category_id">指定為：</label>
                {{ Form::open(['route' => ['admin.storeClassTea',$yearClass->id], 'method' => 'POST','id'=>'storeTea','onsubmit'=>'return false;']) }}
                {{ Form::select('user_id', $users, null, ['id' => 'user_id', 'class' => 'form-control', 'placeholder' => '請選擇老師']) }}
                <br>
                <button class="btn btn-success btn-xs" onclick="bbconfirm('storeTea','請確定指定的老師姓名是否正確？')">送出</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection
@include('layouts.partials.bootbox')