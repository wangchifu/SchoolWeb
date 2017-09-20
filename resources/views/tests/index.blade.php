@extends('layouts.master')

@section('page-title', '問卷系統-填寫')

@section('content')
    <div class="page-header">
        <h1>問卷系統</h1>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="{{ route('tests.index') }}">問卷</a></li>
        @can('create', App\Test::class)
            <li><a href="{{ route('tests.admin') }}">管理</a></li>
        @endcan
    </ul>
    <br><br>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>填寫問卷</h4>
                </div>
                <div class="panel-content">
                    <table class="table table-striped">
                        <thead><th>名稱</th><th>建立者</th><th>動作</th></thead>
                        <tbody>
                            @foreach($tests as $test)
                                <tr>
                                    <td>
                                        {{ $test->name }}
                                    </td>
                                    <td>
                                        {{ $test->user->name }}
                                    </td>
                                    <td>
                                        <?php
                                            $answers = \App\Answer::where('test_id','=',$test->id)->where('user_id','=',auth()->user()->id)->get();
                                        $done = (empty($answers->toArray()))?"NO":"YES";
                                        ?>
                                        @if($done == "NO")
                                            <a href="{{  route('answers.create',$test->id) }}" class="btn btn-success">填寫</a>
                                        @else
                                                <a href="{{ route('answers.destroy',$test->id) }}" class="btn btn-danger" onclick="return confirm('確定刪除？')">刪除重寫</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        </div>
    </div>
@endsection