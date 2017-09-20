@extends('layouts.master')

@section('page-title', '問卷系統-填寫')

@section('content')
    <div class="page-header">
        <h1>問卷系統-管理題目</h1>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ route('tests.admin') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> 返回</a>
                    <h3>問卷：{{ $test->name }}</h3>
                </div>
                <div class="panel-content">
                    {{ Form::open(['route' => 'questions.store', 'method' => 'POST']) }}
                    <table class="table table-striped">
                        <thead><th>題號*</th><th>題目*</th><th>說明</th><th>題型*</th></thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="hidden" name="test_id" value="{{ $test->id }}">
                                {{ Form::text('order', null, ['id' => 'order', 'class' => 'form-control', 'placeholder' => '請輸入題號','required'=>'required']) }}
                            </td>
                            <td>
                                {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => '請輸入題目','required'=>'required']) }}
                            </td>
                            <td>
                                {{ Form::text('description', null, ['id' => 'description', 'class' => 'form-control', 'placeholder' => '請輸入題目說明']) }}
                            </td>
                            <td>
                                <?php
                                $types = [
                                    'text'=>'1.文字填空',
                                    'radio'=>'2.單選題',
                                    'checkbox'=>'3.多選題',
                                    'textarea'=>'4.多行文字',
                                ];
                                ?>
                                {{ Form::select('type',$types, null, ['id' => 'type', 'class' => 'form-control', 'placeholder' => '選擇題型','required'=>'required']) }}
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4">選項(「選擇題」才須要填)</th>
                        </tr>
                        <tr>
                            <td colspan="4">
                                {{ Form::textarea('content', null, ['id' => 'content', 'class' => 'form-control', 'rows' => 4, 'placeholder' => '請一行一行打上選項']) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-right"><button class="btn btn-success">新增題目</button></div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well">
                選擇題選項規則：<br>
                第一題選項<br>
                第二題選項<br>
                第三題選項<br>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>管理題目</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>題號</th><th>題目</th><th>作答</th></tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                                <tr>
                                    <td>
                                        ({{ $question->order }})<a href="{{ route('questions.destroy',$question->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除')">刪除</a>
                                    </td>
                                    <td>
                                        <dt> {{ $question->title }}</dt><br>
                                        <div class="text-primary">({{ $question->description }})</div>
                                    </td>
                                    <td>
                                        @if($question->type == "text")
                                            <input name="Q{{ $question->id }}" type="{{ $question->type }}" class="form-control">
                                        @elseif($question->type == "radio" or  $question->type == "checkbox")
                                            <?php
                                                $items = explode("\r\n",$question->content);
                                            ?>
                                            @foreach($items as $k=>$v)
                                                <input name="Q{{ $question->id }}[]" type="{{ $question->type }}" style="zoom:150%;"> {{ $v }}<br>
                                            @endforeach
                                        @elseif($question->type == "textarea")
                                            <textarea name="Q{{ $question->id }}" class="form-control"></textarea>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection