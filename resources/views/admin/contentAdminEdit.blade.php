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
                <h4>編輯內容</h4>
            </div>
                {{ Form::model($content, ['route' => ['admin.contentUpdate', $content->id], 'method' => 'PATCH']) }}
            <div class="panel-body forum-content">
                <div class="form-group">
                 {{ Form::text('title', $content->title, ['id' => 'title', 'class' => 'form-control', 'placeholder' => '標題']) }}
                </div>
                <div class="form-group">
                    <textarea id="my-editor" name="content" class="form-control">{!! $content->content !!}</textarea>
                </div>
                    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
                <script>
                    var options = {
                        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
                        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token=',
                        allowedContent: true,
                    };
                </script>
                <script>
                    CKEDITOR.replace('my-editor', options);
                </script>
                <br>
                <div class="text-right">
                    <button type="submit" class="btn btn-success">修改</button>
                </div>
            </div>
                {{ Form::close() }}
        </div>
        </div>


    </div>



@endsection