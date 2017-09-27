@extends('layouts.master')

@section('page-title', '報修系統')

@section('content')
    <div class="page-header">
        <h1>報修系統</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3>{{ $fun->name }}</h3>
            <div>
                <a href="{{ route('fixes.index') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> 返回</a>
                <a class="btn btn-success" href="{{ route('fixes.create',$fun->id) }}" role="button"><span class="glyphicon glyphicon-plus"></span> 我要報修</a>
                <a href="{{ route('fixes.select',['id'=>$fun->id,'undone'=>'1']) }}" class="btn btn-info"><span class="glyphicon glyphicon-th-list"></span> 列出未完成</a>
            </div>
        </div>
        <div class="panel-content">

            @foreach($fixes as $fix)
                <?php
                $content = str_replace(chr(13) . chr(10), '<br>', $fix->content);
                ?>
                <div>
                    <div>
                    @if($fix->done=="1")
                            <h4><span class="label label-success"><span class="glyphicon glyphicon-ok-sign"></span> 已修復</span> {{ $fix->title }}</h4>
                    @else
                            <h4><span class="label label-warning"><span class="glyphicon glyphicon-paperclip"></span> 待修中</span> {{ $fix->title }}</h4>
                    @endif
                    </div>

                    <hr>
                    {!! $content !!}<br>
                    {{ $fix->user->name }} - {{ $fix->created_at }}
                    @if($fix->fun->username == auth()->user()->username)
                        <?php
                            if($fix->done=="1"){
                                $checked="checked=checked";
                            }else{
                                $checked="";
                            }
                        ?>
                        <div>
                            <p class="text-danger"><span class="glyphicon glyphicon-comment"></span> 回覆：{{ $fix->reply }}</p>
                            {{ Form::open(['route' => ['fixes.update',$fix->id], 'method' => 'PATCH']) }}
                            <table>
                                <tr>
                                    <td>
                                        完成：
                                    </td>
                                    <td>
                                        <input name="done" type="checkbox" value="1" style="zoom:200%" {{ $checked }}>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        留言：
                                    </td>
                                    <td>
                                        <input name="reply" type="test" value="{{ $fix->reply }}">
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-xs">送出</button> <a href="{{ route('fixes.destroy',$fix->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除這則報修？');">刪</a>
                                    </td>
                                </tr>
                            </table>
                            {{ Form::close() }}
                        </div>
                    @else
                        <div>
                            <p class="text-danger"><span class="glyphicon glyphicon-comment"></span> 回覆：{{ $fix->reply }}</p>
                        </div>
                    @endif
                    <hr size="12px">
                </div>
            @endforeach
                <div>
                    {{ $fixes->links() }}
                </div>
        </div>
        <div class="panel-footer">
            負責人員：{{ $fun->username }}
        </div>
    </div>
@endsection