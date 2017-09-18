@extends('layouts.master')

@section('page-title', '新增公告')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="page-header">
                <h1>
                    {{ $fun->name }} - 新增報修
                </h1>
            </div>
        </div>

        <div class="col-md-8">

            {{ Form::open(['route' => 'fixes.store', 'method' => 'POST']) }}
            <div class="form-group">
                <label for="title">標題*：</label>
                {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => '請輸入標題','required'=>'required']) }}
            </div>

            <div class="form-group">
                <label for="content">內文*：</label>
                {{ Form::textarea('content', "設備地點：\r\n待修狀況：", ['id' => 'content', 'class' => 'form-control', 'rows' => 10, 'placeholder' => '請寫清楚地點及維修內容','required'=>'required']) }}
            </div>
            <input type="hidden" name="fun_id" value="{{ $fun->id }}">
            <a href="{{ route('fixes.select',$fun->id) }}" class="btn btn-default">返回</a> <button class="btn btn-success">填好送出</button>

            {{ Form::close() }}

        </div>

    </div>
@endsection