@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin') }}">使用者管理</a></li>
        <li><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li class="active"><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br><br>
    @include('layouts.partials.alert')
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>區塊管理</h4>
                </div>
                <div class="panel-body forum-content">
                    {{ Form::open(['route' => 'admin.storeBlock', 'method' => 'POST']) }}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>名稱</th>
                            <th>位置</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '名稱']) }}
                            </td>
                            <td>
                                {{ Form::text('style', null, ['id' => 'style', 'class' => 'form-control', 'placeholder' => '位置：R,L,D']) }}
                            </td>
                            <td>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">新增</button>
                                </div>
                                {{ Form::close() }}
                            </td>
                        </tr>
                        @foreach($blocks as $block)
                            {{ Form::model($block, ['route' => ['admin.updateBlock', $block->id], 'method' => 'PATCH']) }}
                            <tr>
                                <td>
                                    {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '名稱']) }}
                                </td>
                                <td>
                                    {{ Form::text('style', null, ['id' => 'style', 'class' => 'form-control', 'placeholder' => '位置']) }}
                                </td>
                                <td>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info btn-xs" onclick="return confirm('是否確定修改？看清楚喔！');">修</button> <a href="{{ route('admin.destroyBlock',$block->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除？看清楚喔！');">刪</a>
                                    </div>
                                </td>
                            </tr>
                            {{ Form::close() }}
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>連結管理</h4>
                </div>
                <div class="panel-body forum-content">

                    {{ Form::open(['route' => 'admin.storeLink', 'method' => 'POST']) }}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>標題</th>
                            <th>連結</th>
                            <th>區塊</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => '標題']) }}
                            </td>
                            <td>
                                {{ Form::text('link', null, ['id' => 'link', 'class' => 'form-control', 'placeholder' => '連結']) }}
                            </td>
                            <td>
                                {{ Form::select('block_id', $blocks2, null, ['id' => 'block_id', 'class' => 'form-control', 'placeholder' => '請選擇區塊']) }}
                            </td>
                            <td>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">新增</button>
                                </div>
                                {{ Form::close() }}
                            </td>
                        </tr>
                        @foreach($links as $link)
                            {{ Form::model($link, ['route' => ['admin.updateLink', $link->id], 'method' => 'PATCH']) }}
                            <tr>
                                <td>
                                    {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => '標題']) }}
                                </td>
                                <td>
                                    {{ Form::text('link', null, ['id' => 'link', 'class' => 'form-control', 'placeholder' => '連結']) }}
                                </td>
                                <td>
                                    {{ Form::select('block_id', $blocks2, $link->block_id, ['id' => 'block_id', 'class' => 'form-control', 'placeholder' => '請選擇區塊']) }}
                                </td>
                                <td>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info btn-xs" onclick="return confirm('是否確定修改？看清楚喔！');">修</button> <a href="{{ route('admin.destroyLink',$link->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除？看清楚喔！');">刪</a>
                                    </div>
                                </td>
                            </tr>
                            {{ Form::close() }}
                        @endforeach

                        </tbody>
                    </table>


                </div>
            </div>
        </div>

    </div>
@endsection