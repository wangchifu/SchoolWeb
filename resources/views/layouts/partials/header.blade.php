<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home.index') }}">{{ env('SCHOOL_NAME') }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('posts.index') }}">公告系統</a></li>
                <li><a href="{{ route('openfiles.index') }}">公開文件</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">校內行政
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('mornings.index') }}">會議文稿</a></li>
                        <li><a href="{{ route('schoolplans.index') }}">校務計畫</a></li>
                        <li><a href="{{ route('fixes.index') }}">報修系統</a></li>
                        <li><a href="{{ route('tests.index') }}">問卷系統</a></li>
                        <li><a href="#">教室預約(x)</a></li>
                        <li><a href="#">運動會報名(x)</a></li>
                        <li><a href="#">午餐系統(x)</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (auth()->check())
                    @if(auth()->user()->admin)
                <li><a href="{{ url('admin') }}">系統設定</a></li>
                    @endif
                <li><a href="#" onclick="hi()">Hi, {{ auth()->user()->name }}</a></li>
                <li><a href="{{ route('perSetup.index') }}"><span class="glyphicon glyphicon-user"></span>個人設定</a></li>
                <li>

                    <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <span class="glyphicon glyphicon-log-out"></span>登出
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
                @else
                <li><a href="{{ url('login') }}">登入</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@if(auth()->check())
<script>
    var i=0;
    function hi()
    {
        i++;
        alert('Hi, {{ auth()->user()->name }}, 你按了'+ i +'下');
    }
</script>
@endif