@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')

    <div class="page-header">
        <h1>內容管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin') }}">使用者管理</a></li>
        <li><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li class="active"><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br><br>
    <div class="row">
        <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>新增內容</h4>
            </div>
                {{ Form::open(['route' => 'admin.contentStore', 'method' => 'POST']) }}
            <div class="panel-body forum-content">
                <div class="form-group">
                 {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => '標題']) }}
                </div>
                <div class="form-group">
                    <textarea id="my-editor" name="content" class="form-control">{!! old('content', 'test editor content') !!}</textarea>
                </div>
                    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
                <script>
                    var options = {
                        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
                        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
                    };
                </script>
                <script>
                    CKEDITOR.replace('my-editor', options);
                </script>
                <br>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">新增</button>
                </div>
            </div>
                {{ Form::close() }}
        </div>
        </div>


    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>已存內容</h4>
                </div>
                <div class="panel-body forum-content">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>標題</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contents as $content)
                            <tr>
                                <th scope="row">{{ $content->id }}</th>
                                <td><a href="{{ route('content.show',$content->id) }}" target="_blank">{{ $content->title }}</a></td>
                                <td><a href="{{ route('admin.contentEdit',$content->id) }}" class="btn btn-info btn-xs">修改</a> <a href="{{ route('admin.contentDestroy',$content->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除？看清楚喔！');">刪除</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection