@extends('layouts.master')

@section('page-title', '教職員工首頁')

@section('content')
    <div class="page-header">
        <h1>教職員工</h1>
    </div>
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body forum-content">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>職稱</th>
                        <th>姓名</th>
                        <th>郵件</th>
                    </tr>
                    </thead>
                    <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->job_title }}</td>
                        <td>{{ $user->name }}
                        @if($user->website)
                             <a href="{{ $user->website }}" target="_blank"><span class="glyphicon glyphicon-globe"></span></a>
                        @endif
                        </td>
                        @if($user->email)
                            <td>{{ $user->email }}</td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-2">
    </div>
@endsection