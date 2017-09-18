@extends('layouts.master')

@section('page-title', '報修系統')

@section('content')
    <div class="page-header">
        <h1>報修系統</h1>
    </div>
    @foreach($funs as $fun)
        <a href="{{ route('fixes.select',$fun->id) }}" class="btn btn-info">{{ $fun->name }}</a>

    @endforeach
@endsection