@extends('layouts.master')

@section('content')
    <div class="alert alert-danger" role="alert">
        <h1>你沒有被授權來這裡！</h1>
    </div>
    <button class="btn btn-default" onclick="history.back()">返回</button>
@endsection