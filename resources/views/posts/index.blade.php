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
            <?php $updated = substr($post->published_at,0,10); ?>
            <tr>
                <th scope="row">{{ $updated }}</th>
                <td><a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a></td>
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