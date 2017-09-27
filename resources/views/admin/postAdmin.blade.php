@extends('layouts.master')

@section('page-title', '系統設定')

@section('content')
    <div class="page-header">
        <h1>系統管理</h1>
    </div>
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin') }}">使用者管理</a></li>
        <li><a href="">學生管理</a></li>
        <li><a href="{{ url('admin/funAdmin') }}">指定管理</a></li>
        <li class="active"><a href="{{ url('admin/postAdmin') }}">公告管理</a></li>
        <li><a href="{{ url('admin/reportAdmin') }}">報告管理</a></li>
        <li><a href="{{ url('admin/linkAdmin') }}">連結管理</a></li>
        <li><a href="{{ url('admin/contentIndex') }}">內容管理</a></li>
        <li><a href="{{ url('admin/fileAdmin') }}">檔案管理</a></li>
    </ul>
    <br><br>
    <div class="row">
    <div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>公告管理</h4>
        </div>
        <div class="panel-body forum-content">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>日期</th>
                    <th>公告標題</th>
                    <th>發佈者</th>
                    <th>分類</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <?php $updated = substr($post->published_at,0,10); ?>
                    <tr>
                        <th scope="row">{{ $updated }}</th>
                        <td><a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a></td>
                        <td>{{ $post->who_do }}</td>
                        <td>{{ $post->category->name }}</td>
                        <td><a href="{{ route('admin.postDel',$post->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('是否確定刪除？看清楚喔！');">刪除</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <nav class="text-center" aria-label="Page navigation">
                {{ $posts->links() }}
            </nav>
        </div>
    </div>
    </div>
        <div class="col-md-3">
            <div class="well">
                <h4>分類管理</h4>
                {{ Form::open(['route' => 'admin.storeCategory', 'method' => 'POST']) }}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>名稱</th>
                        <th>動作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '分類名稱']) }}
                        </td>
                        <td>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">新增</button>
                            </div>
                            {{ Form::close() }}
                        </td>
                    </tr>
                    @foreach($categories as $category)
                    {{ Form::model($category, ['route' => ['admin.updateCategory', $category->id], 'method' => 'PATCH']) }}
                    <tr>
                        <td>
                            {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => '分類名稱']) }}
                        </td>
                        <td>
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">修改</button>
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
@endsection