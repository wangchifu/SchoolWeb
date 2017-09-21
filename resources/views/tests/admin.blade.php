@extends('layouts.master')

@section('page-title', '問卷系統-管理')

@section('content')
    <div class="page-header">
        <h1>問卷系統</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ route('tests.index') }}">問卷</a></li>
        <li class="active"><a href="{{ route('tests.admin') }}">管理</a></li>
    </ul>
    <br><br>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>新增問卷</h4>
        </div>
        <div class="panel-content">
            {{ Form::open(['route' => 'tests.store', 'method' => 'POST']) }}
            <table class="table table-striped">
                <thead><th>名稱*</th><th>填寫人員*</th><th>截止日期*</th><td>建立者</td><th>動作</th></thead>
                <tbody>
                    <tr>
                        <td>
                            {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '請輸入名稱','required'=>'required']) }}
                        </td>
                        <td>
                            <?php
                            $groups = [
                                '1'=>'1.行政人員',
                                '4'=>'4.級任老師',
                                '5'=>'5.科任老師',
                                '9'=>'9.一般職員(非教師)',
                                '1+4+5'=>'10.全校教師(不含職員)',
                                '1+4+5+9'=>'19.所有註冊會員',
                            ];
                            ?>
                            {{ Form::select('do',$groups, null, ['id' => 'do', 'class' => 'form-control', 'placeholder' => '請選擇填寫人員','required'=>'required']) }}
                        </td>
                        <td>
                            <script src="{{ asset('js/cal/jscal2.js') }}"></script>
                            <script src="{{ asset('js/cal/lang/cn.js') }}"></script>
                            <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/jscal2.css') }}">
                            <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/border-radius.css') }}">
                            <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/steel/steel.css') }}">
                            <input id="unpublished_at" name="unpublished_at" class="form-control" placeholder ="請輸入截止日期" required="required">
                            <script>
                                Calendar.setup({
                                    dateFormat : '%Y-%m-%d',
                                    inputField : 'unpublished_at',
                                    trigger    : 'unpublished_at',
                                    onSelect   : function() { this.hide();}
                                });
                            </script>
                        </td>
                        <td>
                            {{ auth()->user()->name }}
                        </td>
                        <td>
                            <button class="btn btn-success" onclick="return confirm('是否確定新增？');">新增問卷</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            {{ Form::close() }}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>管理問卷</h4>
        </div>
        <div class="panel-content">
            <table class="table table-striped">
                <thead><th>啟用？</th><th>名稱*</th><th>填寫人員*</th><th>截止日期*</th><th>建立者</th><th>動作</th></thead>
                <tbody>
                @foreach($tests as $test)
                    {{ Form::model($test,['route' => ['tests.update',$test->id], 'method' => 'PATCH']) }}
                    <tr>
                        <td>
                            <?php
                                if($test->active=="1"){
                                    $checked="checked=checked";
                                }else{
                                    $checked="";
                                }
                            ?>
                            <input name="active" type="checkbox" value="1" class="form-control" style="zoom:80%" {{ $checked }}>
                        </td>
                        <td>
                            {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '請輸入名稱','required'=>'required']) }}
                        </td>
                        <td>
                            {{ Form::select('do',$groups, null, ['id' => 'do', 'class' => 'form-control', 'placeholder' => '請選擇填寫人員','required'=>'required']) }}
                        </td>
                        <td>
                            <input id="unpublished_at" name="unpublished_at" value="{{ $test->unpublished_at }}" class="form-control" placeholder ="請輸入截止日期" required="required">
                        </td>
                        <td>
                            {{ $test->user->name }}
                        </td>
                        <td>
                            @if( auth()->user()->id == $test->user_id)
                                <button class="btn btn-info btn-xs" onclick="return confirm('是否確定修改？');">儲存修改</button> <a href="{{ route('questions.index',$test->id) }}" class="btn btn-success btn-xs">管理題目</a> <a href="{{ route('tests.download',['test'=>$test->id,'type'=>'csv']) }}" class="btn btn-primary btn-xs">下載結果[CSV]</a> <a href="{{ route('tests.download',['test'=>$test->id,'type'=>'xls']) }}" class="btn btn-primary btn-xs">下載結果[EXCEL]</a> <a href="{{ route('tests.destroy',$test->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除？');">刪除</a>
                            @endif
                        </td>
                    </tr>
                    {{ Form::close() }}
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection