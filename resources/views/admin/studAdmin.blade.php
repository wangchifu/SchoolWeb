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
    <br><br>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>學生管理</h4>
                </div>
                <div class="panel-body forum-content">



                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well">
                <h4>新增學年班級</h4>
                {{ Form::open(['route' => 'admin.storeYearClass', 'method' => 'POST']) }}
                <table class="table table-striped">
                    <tr>
                        <td>
                            學年學期
                        </td>
                        <td>
                            {{ Form::text('semester', null, ['id' => 'semester', 'class' => 'form-control', 'placeholder' => '學年學期：1061','required'=>'required']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            一年級
                        </td>
                        <td>
                            {{ Form::text('class1', null, ['id' => 'class1', 'class' => 'form-control', 'placeholder' => '班級數','required'=>'required']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            二年級
                        </td>
                        <td>
                            {{ Form::text('class2', null, ['id' => 'class2', 'class' => 'form-control', 'placeholder' => '班級數','required'=>'required']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            三年級
                        </td>
                        <td>
                            {{ Form::text('class3', null, ['id' => 'class3', 'class' => 'form-control', 'placeholder' => '班級數','required'=>'required']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            四年級
                        </td>
                        <td>
                            {{ Form::text('class4', null, ['id' => 'class4', 'class' => 'form-control', 'placeholder' => '班級數']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            五年級
                        </td>
                        <td>
                            {{ Form::text('class5', null, ['id' => 'class5', 'class' => 'form-control', 'placeholder' => '班級數']) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            六年級
                        </td>
                        <td>
                            {{ Form::text('class6', null, ['id' => 'class6', 'class' => 'form-control', 'placeholder' => '班級數']) }}
                        </td>
                    </tr>
                </table>
                <button class="btn btn-success">新增</button>


                {{ Form::close() }}
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>管理者清單</h4>
                </div>
                <div class="panel-body forum-content">

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well">
                <h4>現有學年班級</h4>
            </div>
        </div>
    </div>


@endsection