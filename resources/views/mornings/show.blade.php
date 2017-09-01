@extends('layouts.master')

@section('page-title', $morning->name)

@section('content')
    <div class="row">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('home.index') }}">首頁</a></li>
                <li><a href="{{ route('mornings.index') }}">會議文稿</a></li>
                <li class="active">{{ $morning->name }}</li>
            </ol>

            <div class="page-header">
                <h1>
                    {{ $morning->name }}
                    <small>
                        <span class="glyphicon glyphicon-download-alt"></span>
                        <a href="{{ url('mornings')."/".$morning->id."/download" }}"><button class="btn btn-default">報告內容下載</button></a>
                    </small>
                </h1>
            </div>
        </div>
        @if($overDay)
            <div class="text-left">
                <div class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-ban-circle"></span>已鎖定</div> <a href="{{ url('mornings/index') }}"><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-chevron-left"></span> 返回</button></a>
            </div>
            <br>
        @else
        @can('createReport', App\Morning::class)
            <div class="text-left">
                {{ $overDay }}
                <a class="btn btn-success btn-xs" href="{{url('mornings/'.$morning->id.'/createReport')}}" role="button"><span class="glyphicon glyphicon-plus"></span> 新增處室報告</a> <a href="{{ url('mornings/index') }}"><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-chevron-left"></span> 返回</button></a>
            </div>
            <br>
        @endcan
        @endif
        <div class="col-md-8">
            <?php $count=0; ?>
            @foreach($order_reports as $k=>$v)
            <?php $count++; ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $count.'.'.$v['who_do'] }}</h4>

                </div>




                <div class="panel-body forum-content">
                    <p>{!! $v['content'] !!}</p>
                    @if($v['user_id']==auth()->user()->id & @!$overDay)
                    <div class="text-right">
                        <a class="btn btn-info btn-xs" href="{{ url('/mornings').'/'.$morning->id.'/editReport/'.$v['id'] }}" role="button"><span class="glyphicon glyphicon-pencil"></span> 修改報告</a>
                        {{ Form::open(['route' => ['mornings.destroyReport', $v['id']], 'method' => 'DELETE', 'style' => 'display: inline-block']) }}
                        <button type="submit" class="btn btn-danger btn-xs" role="button" onclick="return confirm('是否確定刪除此報告？看清楚喔！');"><span class="glyphicon glyphicon-trash"></span> 刪除</button>
                        {{ Form::close() }}
                    </div>
                    @endif
                </div>
                <div class="panel-footer">

                    附檔：
                    <?php $i=0; ?>
                    @foreach($v['mfiles'] as $mfile)
                        <?php $i++; ?>
                        @if ($mfile->name)
                            <a href ="{{url('downloadMfile'.'/'.$mfile->name)}}"><button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-download-alt"></span> 檔案{{ $i }}</button></a>
                        @endif
                    @endforeach
                    <!--
                    @if($v['user_id']==auth()->user()->id)
                    <br><br>
                    {{ Form::open(['route' => 'mornings.addFile', 'method' => 'POST', 'files' => true]) }}
                    <input name="upload[]" type="file" multiple>
                    {{ Form::hidden('morning_id', $morning->id) }}
                    {{ Form::hidden('report_id', $v['id']) }}
                        <div class="text-right">
                            <button type="submit" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-arrow-up"></span> 新增附檔</button>
                        </div>
                        <small>檔案可多選，單檔大小大於5MB自動略過</small>
                    {{ Form::close() }}

                    @endif
                    -->

                </div>
            </div>

                @endforeach
        </div>

        <div class="col-md-4">

            <div class="well">
                <h4>統計資訊</h4>
                <div>
                    <span class="glyphicon glyphicon-user"></span>
                     會議建立：{{ $morning->who_do }}
                </div>
                <div>
                    <span class="glyphicon glyphicon-time"></span>
                     建立時間：{{ $morning->created_at }}
                </div>
                <div>
                    <span class="glyphicon glyphicon-dashboard"></span>
                     修改時間：{{ $morning->updated_at }}
                </div>
                <div>
                    <span class="glyphicon glyphicon-list"></span>
                    報告人次：{{ $count }} 人
                </div>
            </div>



        </div>

    </div>
@endsection