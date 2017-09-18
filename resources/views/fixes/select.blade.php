@extends('layouts.master')

@section('page-title', '報修系統')

@section('content')
    <div class="page-header">
        <h1>報修系統</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3>{{ $fun->name }}</h3>
        </div>
        <div class="panel-content">
            <div>
                <a href="{{ route('fixes.index') }}" class="btn btn-default">返回</a> <a class="btn btn-success" href="{{ route('fixes.create',$fun->id) }}" role="button"><span class="glyphicon glyphicon-plus"></span>我要報修</a>
            </div>

            @foreach($fixes as $fix)
                <?php
                    $content = str_replace(chr(13) . chr(10), '<br>', $fix->content);
                ?>
            <div>
                <h4><span class="glyphicon glyphicon-paperclip"></span>{{ $fix->title }}</h4>
                <hr>
                {!! $content !!}<br>
                {{ $fix->user->name }} - {{ $fix->created_at }}
                <hr size="12px">

            @endforeach
            </div>
                {{ $fixes->links() }}
            </div>
        </div>
        <div class="panel-footer">
            負責人員：{{ $fun->username }}
        </div>
    </div>
@endsection