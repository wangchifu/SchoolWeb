@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')

    <div class="page-header">
        <h1>檔案管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin') }}">使用者管理</a></li>
        <li><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li class="active"><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br><br>
    <div class="row">
    <div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>內容管理</h4>
        </div>
        <div class="panel-body forum-content">
            <textarea id="my-editor" name="content" class="form-control">{!! old('content', 'test editor content') !!}</textarea>
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
        </div>
    </div>
    </div>


    </div>

@endsection