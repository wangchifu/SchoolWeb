@extends('layouts.master')

@section('page-title', '公開文件首頁')

@section('content')

    <div class="page-header">
        <h1>公開文件</h1>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
            {!! $folder_path !!}
            </div>
            <div class="panel-body forum-content">
                <table>
                @foreach($uploads as $upload)
                    <tr>
                        <td>
                        @if($upload->type==1)
                        <a href="{{ route('openfiles.show',$upload->id) }}"><span class="glyphicon glyphicon-folder-open"></span> {{ $upload->name }}</a>
                        @else
                            <?php $filename = explode('&',$upload->name); ?>
                            <a href="{{ route('openfiles.downloadfile',$upload->name) }}"><span class="glyphicon glyphicon-file"></span> {{ $filename[1] }}</a>
                        @endif
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
            <div class="panel-footer">
                @if(auth()->check())
                    @if(auth()->user()->group_id == "1")
                        @if($who_do==auth()->user()->job_title)
                {{ Form::open(['route' => 'openfiles.store', 'method' => 'POST']) }}
                <table>
                    <tr>
                        <td>{{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '請入目錄名稱','required'=>'required']) }}</td><td><button type="submit" class="btn btn-success">新增目錄</button></td>
                    </tr>

                <input type="hidden" name="type" value="1">
                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                {{ Form::close() }}
                {{ Form::open(['route' => 'openfiles.store', 'method' => 'POST','files'=>true]) }}

                    <tr>
                        <td><input name="upload[]" type="file" required="required" multiple></td><td><button type="submit" class="btn btn-info">新增檔案</button></td>
                    </tr>
                </table>
                <input type="hidden" name="type" value="2">
                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                {{ Form::close() }}
                        @endif
                    @endif
                @endif

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="well">
            <h4>編輯提示</h4>
            <div>
                <ul>
                    <li>此為公開文件上傳，請注意個資及版權問題。</li>
                    <li>單檔10MB以下。</li>
                </ul>
            </div>
        </div>
    </div>

@endsection