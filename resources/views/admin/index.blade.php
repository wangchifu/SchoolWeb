@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{ url('admin') }}">使用者管理</a></li>
        <li><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br><br>
    <div class="row">
    <div class="col-md-10">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>現職管理</h4>
        </div>
        <div class="panel-body forum-content">
            {{ Form::open(['route' => 'admin.storeUser', 'method' => 'POST']) }}
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>帳號</th>
                    <th>姓名</th>
                    <th>Email</th>
                    <th>職稱</th>
                    <th>群組排序</th>
                    <th>管理權</th>
                    <th>動作</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="form-group">
                            {{ Form::text('username', 'hd', ['id' => 'username', 'class' => 'form-control']) }}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '姓名']) }}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            {{ Form::text('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email']) }}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            {{ Form::text('job_title', null, ['id' => 'job_title', 'class' => 'form-control', 'placeholder' => '職稱']) }}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            {{ Form::text('group_id', null, ['id' => 'group_id', 'class' => 'form-control', 'placeholder' => '群組排序']) }}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            {{ Form::text('admin', null, ['id' => 'admin', 'class' => 'form-control', 'placeholder' => '管理權']) }}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">新增</button>
                        </div>
                    </td>
                </tr>
                {{ Form::close() }}
                @foreach($users1 as $user)
                    {{ Form::model($user, ['route' => ['admin.updateUser', $user->id], 'method' => 'PATCH']) }}
                    <tr>
                        <td>
                            <div class="form-group">
                                <a class="btn btn-primary btn-xs" href="{{ route('admin.resetUser', $user->id) }}" role="button" onclick="return confirm('是否確定重設此帳密的密碼？看清楚喔！');"><span class="glyphicon glyphicon-retweet"></span></a>
                                <a class="btn btn-danger btn-xs" href="{{ route('admin.unactiveUser', $user->id) }}" role="button" onclick="return confirm('確定停用帳號 {{$user->username}} {{ $user->name }}？看清楚喔！');"><span class="glyphicon glyphicon-remove"></span></a>
                                {{ $user->username }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '姓名']) }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                {{ Form::text('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email']) }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                {{ Form::text('job_title', null, ['id' => 'job_title', 'class' => 'form-control', 'placeholder' => '職稱']) }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                {{ Form::text('group_id', null, ['id' => 'group_id', 'class' => 'form-control', 'placeholder' => '群組排序']) }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                {{ Form::text('admin', null, ['id' => 'admin', 'class' => 'form-control', 'placeholder' => '無管理權']) }}
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info" onclick="return confirm('確定修改？');">修改</button>
                            </div>
                        </td>
                    </tr>
                    {{ Form::close() }}
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
    </div>
        <div class="col-md-2">
            <div class="well">
                <h4>群組排序</h4>
                <p>第一碼：</p>
                <p>校　　長：110</p>
                <p>主　　任：2xx</p>
                <p>組　　長：3xx</p>
                <p>級任教師：4xx</p>
                <p>科任教師：500</p>
                <p>一般職員：999</p>
                <p>第二碼：</p>
                <p>校長室　：110</p>
                <p>教務處　：x20</p>
                <p>學務處　：x30</p>
                <p>總務處　：x40</p>
                <p>輔導室　：x50</p>
                <p>會計室　：x60</p>
                <p>人事室　：x70</p>
                <p>第三碼：流水號</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>離職管理</h4>
                </div>
                <div class="panel-body forum-content">
                    @foreach($users2 as $user)
                        <a href="{{ route('admin.activeUser',$user->id) }}" class="btn btn-success btn-xs" onclick="return confirm('確定回復帳號 {{$user->username}} {{ $user->name }}？看清楚喔！');"><span class="glyphicon glyphicon-chevron-up"></span></a> {{ $user->username }} {{ $user->name }}
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-2">
        </div>
    </div>
@endsection