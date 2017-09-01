@extends('layouts.master')

@section('page-title', '編輯公告')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('home.index') }}">首頁</a></li>
                <li><a href="{{ route('posts.index') }}">公告系統</a></li>
                <li class="active">編輯公告</li>
            </ol>

            <div class="page-header">
                <h1>
                    編輯公告
                </h1>
            </div>
        </div>

        <div class="col-md-8">

            @include('layouts.partials.alert')

            {{ Form::model($post, ['route' => ['posts.update', $post->id], 'method' => 'PATCH', 'files' => true]) }}

                @include('posts.partials.form')
            附檔：<br>
            @foreach($post->pfiles as $pfile)
                @if ($pfile->name)
                    <?php $filename = explode('&',$pfile->name);?>
                    <a href ="{{ route('posts.delPfile',$pfile->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除附檔 {{$filename[1]}} ？');"><span class="glyphicon glyphicon-remove"></span>{{ $filename[1] }}</a>
                @endif
            @endforeach
            <br>
            <br>
            <input name="upload[]" type="file" multiple>
                <div class="text-right">
                    <a href="{{ route('posts.index') }}" class="btn btn-link">返回</a>
                    <button type="submit" class="btn btn-success">更新</button>
                </div>

            {{ Form::close() }}

        </div>

        <div class="col-md-4">

            <div class="well">
                <h4>編輯提示</h4>
                <div>
                    <ul>
                    <li>下架時間不填者，一年後自動下架。</li>
                    <li>請注意上傳檔案的版權。</li>
                    <li>請注意上傳檔案的個資問題。</li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
@endsection