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
                    <button class="btn btn-success">新增學生</button>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                                座號
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
                        @foreach($student_data as $k=>$v)
                            <tr>
                                <td>
                                    {{ $k }}
                                </td>
                                <td>
                                    @if($v['性別'] == "男")
                                        <p class="text-primary">{{ $v['姓名'] }}</p>
                                    @else
                                        <p class="text-danger">{{ $v['姓名'] }}</p>
                                    @endif

                                </td>
                                <td>
                                    @if($v['性別'] == "男")
                                        <p class="text-primary">{{ $v['性別'] }}</p>
                                    @else
                                        <p class="text-danger">{{ $v['性別'] }}</p>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-danger">學生轉出</button>
                                </td>
                            </tr>
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
                改為：<input name="username" value="">(請填代號)
            </div>
        </div>
    </div>

@endsection
@include('layouts.partials.bootbox')