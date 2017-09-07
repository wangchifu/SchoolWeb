@extends('layouts.master')

@section('page-title', '公告系統首頁')

@section('content')
    <div class="page-header">
        <h1>公告系統</h1>
    </div>
    @can('create', App\Models\Post::class)
    <div class="text-right">
        <a class="btn btn-success btn-xs" href="{{ route('posts.create') }}" role="button"><span class="glyphicon glyphicon-plus"></span>新增公告</a>
    </div>
    @endcan
    <?php
    $ipAddress = '';

    // Check for X-Forwarded-For headers and use those if found
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ('' !== trim($_SERVER['HTTP_X_FORWARDED_FOR']))) {
        $ipAddress = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
    } else {
        if (isset($_SERVER['REMOTE_ADDR']) && ('' !== trim($_SERVER['REMOTE_ADDR']))) {
            $ipAddress = trim($_SERVER['REMOTE_ADDR']);
        }
    }
    $ip = explode('.',$ipAddress);
    if($ip[0]=="192" and $ip[1]=="168"){
        $client_in = "1";
    }else{
        $client_in = "0";
    }

    ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>日期</th>
            <th>公告標題</th>
            <th>發佈者</th>
            <th>分類</th>
            <th>點閱</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <?php
            $updated = substr($post->published_at,0,10);
            if($post->insite){
                if($client_in){
                    $title = "<a href=\"". route('posts.show', $post->id) ."\"><p class='btn btn-danger btn-xs'>校內文件</p> ". $post->title . "</a>";
                }else{
                    $title = "<p class='btn btn-danger btn-xs'>校內文件</p>";
                }
            }else{
                $title = "<a href=\"". route('posts.show', $post->id) ."\">". $post->title . "</a>";
            };



            ?>
            <tr>
                <th scope="row">{{ $updated }}</th>
                <td>{!! $title !!}</td>
                <td><a href="{{ route('posts.index',['who_do'=>$post->who_do]) }}">{{ $post->who_do }}</a></td>
                <td><a href="{{ route('posts.index',['category_id'=>$post->category_id]) }}">{{ $post->category->name }}</a></td>
                <td>{{ $post->page_view }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav class="text-center" aria-label="Page navigation">
        {{ $posts->links() }}
    </nav>
@endsection