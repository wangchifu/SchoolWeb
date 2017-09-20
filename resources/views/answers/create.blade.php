@extends('layouts.master')

@section('page-title', '新增公告')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="page-header">
                <h1>
                    {{ $test->name }} - 填寫問卷
                </h1>
            </div>
        </div>

        <div class="col-md-8">

            {{ Form::open(['route' => 'answers.store', 'method' => 'POST']) }}
            <div>
                <a href="{{ route('tests.index') }}" class="btn btn-default">返回</a> <button class="btn btn-success" onclick="return confirm('確定送出？無法更改喔！')">填好送出</button>
            </div>
            <table class="table table-striped">
                <thead>
                <tr><th>題號</th><th>題目</th><th>作答</th></tr>
                </thead>
                <tbody>
                @foreach($questions as $question)
                    <tr>
                        <td>
                            ({{ $question->order }})
                        </td>
                        <td>
                            <dt> {{ $question->title }}</dt><br>
                            <div class="text-primary">({{ $question->description }})</div>
                        </td>
                        <td>
                            <input name="test_id" type="hidden" value="{{ $test->id }}">
                            @if($question->type == "text")
                                <input name="Q[{{ $question->id }}]" type="{{ $question->type }}" class="form-control" required="required">
                            @elseif($question->type == "radio" or  $question->type == "checkbox")
                                <?php
                                $items = explode("\r\n",$question->content);
                                ?>
                                @foreach($items as $k=>$v)
                                    <input name="Q[{{ $question->id }}]" type="{{ $question->type }}"  value="{{ $v }}" style="zoom:150%;"> {{ $v }}<br>
                                @endforeach
                            @elseif($question->type == "textarea")
                                <textarea name="Q[{{ $question->id }}]" class="form-control" required="required"></textarea>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div>
                <a href="{{ route('tests.index') }}" class="btn btn-default">返回</a> <button class="btn btn-success" onclick="return confirm('確定送出？無法更改喔！')">填好送出</button>
            </div>
            {{ Form::close() }}

        </div>

    </div>
@endsection