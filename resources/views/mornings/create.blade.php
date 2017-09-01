@extends('layouts.master')

@section('page-title', '新增晨會')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('home.index') }}">首頁</a></li>
                <li><a href="{{ route('mornings.index') }}">會議文稿</a></li>
                <li class="active">新增會議</li>
            </ol>

            <div class="page-header">
                <h1>
                    新增會議
                </h1>
            </div>
        </div>

        <div class="col-md-8">

            {{ Form::open(['route' => 'mornings.store', 'method' => 'POST']) }}

            @include('mornings.partials.form')

            <div class="text-right">
                <a href="{{ route('mornings.index') }}" class="btn btn-link">返回</a>
                <button type="submit" class="btn btn-success">新增會議</button>
            </div>

            {{ Form::close() }}

        </div>

        <div class="col-md-4">

            <div class="well">
                <h4>編輯提示</h4>
                <div>
                    會議當日中午後，將鎖定不得再更改。
                </div>
            </div>

        </div>

    </div>
@endsection