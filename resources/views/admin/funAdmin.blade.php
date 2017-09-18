@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin') }}">使用者管理</a></li>
        <li class="active"><a href="{{ url('admin/funAdmin') }}">指定管理</a></li>
        <li><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br><br>
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>指定管理</h4>
                </div>
                <div class="panel-body forum-content">
                    <?php
                        $select = ["1"=>"報修系統","2"=>"運動會報名"];
                    ?>
                    {{ Form::open(['route' => 'admin.storeFun', 'method' => 'POST']) }}
                    {{ Form::select('type', $select, null, ['id' => 'type', 'class' => 'form-control', 'placeholder' => '請選擇功能']) }}
                    {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '名稱']) }}
                    {{ Form::text('username', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '使用者帳號']) }}
                    <button class="btn btn-success">新增</button>
                    {{ Form::close() }}


                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="well">
                功能代號：<br>
                1 => 報修系統<br>
                2 => 運動會報名<br>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>管理者清單</h4>
                </div>
                <div class="panel-body forum-content">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>功能</th><th>名稱</th><th>管理者</th></tr>
                        </thead>
                        <tbody>
                        @foreach($funs as $fun)
                            <tr><td>{{ $fun->type }}</td><td>{{ $fun->name }}</td><td>{{ $fun->username }}</td></tr><br>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection