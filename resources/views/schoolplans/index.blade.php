@extends('layouts.master')

@section('page-title', '校務計畫首頁')

@section('content')

    <div class="page-header">
        <h1>校務計畫</h1>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
            {!! $folder_path !!}
            </div>
            <div class="panel-body forum-content">
                <table class="table table-bordered">
                @foreach($uploads as $upload)
                        <?php
                        $n = \App\Upload::where('folder_id',$upload->id)->count();
                        ?>
                    <tr>
                        <td>
                        @if($upload->type==1)
                        <a href="{{ route('schoolplans.show',$upload->id) }}"><span class="glyphicon glyphicon-folder-open"></span> {{ $upload->name }} ({{ $n }}</a>
                        @else
                            <?php $filename = explode('&',$upload->name); ?>
                                <a href="{{ route('schoolplans.downloadfile',$upload->name) }}"><span class="glyphicon glyphicon-download-alt"></span> {{ $filename[1] }}</a>
                        @endif
                        </td>
                        @if(auth()->check())
                            @if(auth()->user()->job_title == $upload->who_do)
                                <td>
                                    <a href="{{ route('schoolplans.destroy',$upload->id) }}" class="btn btn-danger btn-xs">刪除</a>
                                </td>
                            @else
                                <td>
                                </td>
                            @endif
                        @endif
                    </tr>
                @endforeach
                </table>
            </div>
            <div class="panel-footer">
                @if(auth()->check())
                    @if(auth()->user()->group_id == "1")
                        @if($who_do==auth()->user()->job_title)
                {{ Form::open(['route' => 'schoolplans.store', 'method' => 'POST']) }}
                <table>
                    <tr>
                        <td>{{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '請入目錄名稱','required'=>'required']) }}</td><td><button type="submit" class="btn btn-success">新增目錄</button></td>
                    </tr>

                <input type="hidden" name="type" value="1">
                <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                {{ Form::close() }}
                {{ Form::open(['route' => 'schoolplans.store', 'method' => 'POST','files'=>true]) }}

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
                    <li>此為校務計畫上傳，請注意個資及版權問題。</li>
                    <li>單檔10MB以下。</li>
                </ul>
            </div>
        </div>
    </div>

@endsection