@extends('layouts.master')

@section('page-title', '新增公告')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('home.index') }}">首頁</a></li>
                <li><a href="{{ route('posts.index') }}">公告系統</a></li>
                <li class="active">新增公告</li>
            </ol>

            <div class="page-header">
                <h1>
                    新增公告
                </h1>
            </div>
        </div>

        <div class="col-md-8">

            @include('layouts.partials.alert')

            {{ Form::open(['route' => 'posts.store', 'method' => 'POST', 'files' => true]) }}

                <input type="hidden" name="page_view" value="0">

                @include('posts.partials.form')
                <div class="form-group">
                     <label for="upload">附件：</label>
                     <input name="upload[]" type="file" multiple>
                </div>
                <div class="text-right">
                    <a href="{{ route('posts.index') }}" class="btn btn-link">返回</a>
                    <button type="submit" class="btn btn-success">新增</button>
                </div>

            {{ Form::close() }}

        </div>

        <div class="col-md-4">

            <div class="well">
                <h4>編輯提示</h4>
                <div>
                    <ul>
                        <li>下架時間不填者，一年後自動下架。</li>
                        <li>附檔單個超過5MB將自動略過不上傳。</li>
                        <li>請注意上傳檔案的版權。</li>
                        <li>請注意上傳檔案的個資問題。</li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
@endsection