@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin') }}">使用者管理</a></li>
        <li><a href="">學生管理</a></li>
        <li><a href="{{ url('admin/funAdmin') }}">指定管理</a></li>
        <li><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li class="active"><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br><br>
    <div class="row">
    <div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>報告管理</h4>
        </div>
        <div class="panel-body forum-content">

                @foreach($reports as $report)
                    <div class="well">
                    <a href="{{ route('mornings.show',$report->morning_id) }}">{{ $report->morning->name }}</a>---->「{{ $report->who_do }}」報告
                        <div class="text-right">
                            <a href="{{ route('admin.reportDel',$report->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除？看清楚喔！');">刪除</a>
                        </div>
                    </div>
                @endforeach
            <nav class="text-center" aria-label="Page navigation">
                {{ $reports->links() }}
            </nav>

        </div>
    </div>
    </div>
        <div class="col-md-3">

        </div>
    </div>
@endsection