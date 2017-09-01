@extends('layouts.master')

@section('page-title', '會議文稿首頁')

@section('content')
    <div class="page-header">
        <h1>會議文稿</h1>
    </div>
    @can('create', App\Morning::class)
        <div class="text-left">
            <a class="btn btn-success btn-xs" href="{{ route('mornings.create') }}" role="button"><span class="glyphicon glyphicon-plus"></span> 新增會議</a>
        </div>
        <br>
    @endcan
    @foreach ($mornings as $morning)
        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body forum-content">
                <div class="media">
                    <div class="media-left">
                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <a href="{{ route('mornings.show', $morning->id)}} ">
                                {{ $morning->name }}
                            </a>
                                <?php
                                //判別是否過期
                            //判別是否過期
                                    $rightNow = date("Y-m-d-H");
                                    if(strpos($morning->name,"教師晨會")){
                                        $deadLine = substr($morning->name,0,10)."-12";
                                        if( $deadLine < $rightNow)
                                        {
                                            $overDay =true;
                                        }else{
                                            $overDay = false;
                                        }
                                    }else{
                                        //校務會議可以到晚上才過期
                                        $deadLine = substr($morning->name,0,10)."-23";
                                        if( $deadLine < $rightNow)
                                        {
                                            $overDay =true;
                                        }else{
                                            $overDay = false;
                                        }
                                    }
                                ?>
                                @if($overDay)
                                    <div class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-ban-circle"></span>已鎖定</div>
                                @endif
                        </h4>
                        @if(auth()->check())
                            @include('mornings.partials.modify-buttons')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <nav class="text-center" aria-label="Page navigation">
        {{ $mornings->links() }}
    </nav>
@endsection