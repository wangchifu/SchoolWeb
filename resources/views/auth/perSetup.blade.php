@extends('layouts.master')

@section('page-title', '更改個人設定')

@section('content')
    <div class="page-header">
        <h1>個人設定</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            修改密碼
        </div>
        <div class="panel-body forum-content">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                 <form id="thForm"  class="form-horizontal" method="POST" action="{{ route('perSetup.updatePassword') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password">新密碼</label>
                        <input id="password" name="password" type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">再輸入一次密碼</label>
                        <input id="password-confirm" name="password-confirm" type="password" class="form-control" name="password_confirmation"  required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="check();return false;">
                            更改密碼
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            個人資料
        </div>
        <div class="panel-body forum-content">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                {{ Form::open(['route' => ['perSetup.updateData',auth()->user()->id], 'method' => 'POST']) }}
                <div class="form-group">
                    <label for="name">姓名*：</label>
                    {{ Form::text('name', auth()->user()->name, ['id' => 'name', 'class' => 'form-control','required'=>'required']) }}
                </div>
                <div class="form-group">
                    <label for="job_title">職稱：</label>
                    {{ auth()->user()->job_title }}
                </div>
                <div class="form-group">
                    <label for="email">Email：</label>
                    {{ Form::text('email', auth()->user()->email, ['id' => 'email', 'class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    <label for="website">網站：</label>
                    {{ Form::text('website', auth()->user()->website, ['id' => 'website', 'class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" onclick="if(confirm('您確定送出嗎?')) return true;else return false">
                        更改個人資料
                    </button>
                </div>
            {{ Form::close() }}
            </div>
            <div class="col-md-3">
            </div>
        </div>
    </div>

<script>
    function check()
    {

            if(document.getElementById('password').value!=document.getElementById('password-confirm').value)
            {
                alert("兩次密碼不一樣！");
                document.getElementById('password').value = "";
                document.getElementById('password-confirm').value = "";
                document.getElementById('password').focus();
                return false;
            }
            else if(document.getElementById('password').value.length < 8){
                alert("密碼長度少於八碼");
                document.getElementById('password').value = "";
                document.getElementById('password-confirm').value = "";
                document.getElementById('password').focus();
                return false;
            }else if(document.getElementById('password').value !=/^[a-zA-Z]{1}+$/)
            {
                alert("首字必要為英文字！");
                document.getElementById('password').value = "";
                document.getElementById('password-confirm').value = "";
                document.getElementById('password').focus();
            }else{
                document.getElementById('theForm').submit();
            }

    }
</script>
@endsection