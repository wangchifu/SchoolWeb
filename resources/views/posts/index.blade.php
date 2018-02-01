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
     if($_SERVER['REMOTE_ADDR'] == env('SCHOOL_IP')){
        $client_in = "1";
    }else{
        $client_in = "0";
    }

    ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th width="110">日期</th>
            <th>公告標題</th>
            <th width="80">發佈者</th>
            <th width="70">分類</th>
            <th width="50">點閱</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <?php
            $updated = substr($post->published_at,0,10);
            $this_date = date("Y-m-d");
            $this_title = mb_substr($post->title,0,55,"UTF-8")."...";

            ?>
            @if($updated <= $this_date)
            <?php
            if($post->insite){
                if($client_in=="1" or auth()->check()){
                    $title = "<a href=\"". route('posts.show', $post->id) ."\"><p class='btn btn-danger btn-xs'>校內</p> ". $this_title . "</a>";
                }else{
                    $title = "<p class='btn btn-danger btn-xs'>校內</p>";
                }
            }else{
                $title = "<a href=\"". route('posts.show', $post->id) ."\">". $this_title . "</a>";
            };



            ?>
            <tr>
                <th scope="row">{{ $updated }}</th>
                <td>{!! $title !!}</td>
                <td><a href="{{ route('posts.index',['who_do'=>$post->who_do]) }}">{{ $post->who_do }}</a></td>
                <td><a href="{{ route('posts.index',['category_id'=>$post->category_id]) }}">{{ $post->category->name }}</a></td>
                <td>{{ $post->page_view }}</td>
            </tr>
            @else
                @if(auth()->check())
                    @if(auth()->user()->job_title == $post->who_do)
                    <?php
                    if($post->insite){
                        if($client_in=="1" or auth()->check()){
                            $title = "[未來公告] <a href=\"". route('posts.show', $post->id) ."\"><p class='btn btn-danger btn-xs'>校內</p> ". $post->title . "</a>";
                        }else{
                            $title = "[未來公告] <p class='btn btn-danger btn-xs'>校內</p>";
                        }
                    }else{
                        $title = "[未來公告] <a href=\"". route('posts.show', $post->id) ."\">". $post->title . "</a>";
                    };



                    ?>
                    <tr>
                        <th scope="row">{{ $updated }}</th>
                        <td>{!! $title !!}</td>
                        <td><a href="{{ route('posts.index',['who_do'=>$post->who_do]) }}">{{ $post->who_do }}</a></td>
                        <td><a href="{{ route('posts.index',['category_id'=>$post->category_id]) }}">{{ $post->category->name }}</a></td>
                        <td>{{ $post->page_view }}</td>
                    </tr>

                    @endif
                @endif
            @endif
        @endforeach
        </tbody>
    </table>
    <nav class="text-center" aria-label="Page navigation">
        {{ $posts->links() }}
    </nav>
@endsection