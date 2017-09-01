@extends('layouts.master')

@section('page-title', '修改處室報告')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('home.index') }}">首頁</a></li>
                <li><a href="{{ route('mornings.index') }}">會議文稿</a></li>
                <li><a href="{{url('/mornings/'.$morning->id)}}">{{ $morning->name }}</a></li>
                <li class="active">修改處室報告</li>
            </ol>
            <div class="page-header">
                <h1>
                    {{ $morning->name }}
                    <small>
                        <span class="glyphicon glyphicon-time"></span>
                        建立時間{{ $morning->created_at->format('Y/m/d') }}
                    </small>
                </h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            @include('layouts.partials.alert')
            <div class="panel panel-default">
            <div class="panel-heading">
                    <h4>{{ auth()->user()->job_title }}</h4>
            </div>

            <div class="panel-body forum-content">
                {{ Form::model($report,['route' => ['mornings.updateReport',$report->id], 'method' => 'PATCH', 'files' => true]) }}
                <div class="form-group">
                    <label for="content">內文*：</label>
                    {{ Form::textarea('content', $report->content, ['id' => 'content', 'class' => 'form-control', 'rows' => 10, 'placeholder' => '請輸入內容']) }}
                </div>
                附檔：<br>
                @foreach($mfiles as $mfile)
                    @if ($mfile->name)
                        <?php $filename = explode('&',$mfile->name);?>
                        <a href ="{{ route('mornings.delMfile',$mfile->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除附檔 {{$filename[1]}} ？');"><span class="glyphicon glyphicon-remove"></span>{{ $filename[1] }}</a>
                    @endif
                @endforeach
                <br>
                <br>
                <input name="upload[]" type="file" multiple>
                <div class="text-right">
                    <a href="{{url('/mornings/'.$morning->id)}}" class="btn btn-link">返回</a>
                    <button type="submit" class="btn btn-info">修改報告</button>
                </div>

                {{ Form::close() }}

            </div>
            <div class="panel-footer">
                附檔可多選，單檔超過5MB將略過不上傳。
            </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="well">
                <h4>編輯提示</h4>
                <div>
                    <ul>
                    <li>使用 ->想要提醒的字<- <br>
                        會變成 <font color="red"><strong>想要提醒的字</strong></font></li>
                    <li>
                        會議報告於會議當日13時後，不得再增修。
                    </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection