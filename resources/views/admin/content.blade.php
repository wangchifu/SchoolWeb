@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')

    <div class="page-header">
        <h1>{{ $content->title }}</h1>
    </div>
    <div class="well">
        {!! $content->content !!}
    </div>
@endsection