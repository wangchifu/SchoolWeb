@extends('layouts.master')

@section('page-title', '更改個人密碼')

@section('content')
    <div class="page-header">
        <h1>修改密碼</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body forum-content">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                 <form id="thForm"  class="form-horizontal" method="POST" action="{{ route('updatePassword') }}">
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
                            更改
                        </button>
                    </div>
                </form>
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