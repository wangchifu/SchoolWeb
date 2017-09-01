@extends('layouts.master')

@section('page-title', $post->title)

@section('content')
    <div class="row">

        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('home.index') }}">首頁</a></li>
                <li><a href="{{ route('posts.index') }}">公告系統</a></li>
                <li class="active">{{ $post->title }}</li>
            </ol>

            <div class="page-header">
                <h1>
                    {{ $post->title }}
                    <small>
                        <span class="glyphicon glyphicon-time"></span>
                        {{ $post->published_at }}
                    </small>
                </h1>
            </div>
        </div>

        <div class="col-md-8">
            <div class="text-left">
                <a href="{{ url('posts') }}"><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-chevron-left"></span> 返回</button></a>
            </div>
            <div class="text-right" style="margin-bottom: 20px;">
                @include('posts.partials.modify-buttons')
            </div>
            <?php $content = str_replace(chr(13) . chr(10), '<br>', $post->content);?>
            <div class="well"><p>{!! $content !!}</p></div>


            <?php $i=0; ?>
            <div>附檔：
            @foreach($post->pfiles as $pfile)
                <?php $i++; ?>
                @if ($pfile->name)
                    <a href ="{{url('downloadPfile'.'/'.$pfile->name)}}"><button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-download-alt"></span> 檔案{{ $i }}</button></a>
                @endif
            @endforeach
            </div>

        </div>

        <div class="col-md-4">

            <div class="well">
                <h4>文章資訊</h4>
                    <div>
                        <span style="font-weight: bold">分類：</span>
                        {{ $post->category->name }}
                    </div>
                    <div>
                        <span style="font-weight: bold">作者：</span>
                        {{ $post->who_do }}
                    </div>
                    <div>
                        <span style="font-weight: bold">建立時間：</span>
                        {{ $post->created_at }}
                    </div>
                    <div>
                        <span style="font-weight: bold">修改時間：</span>
                        {{ $post->updated_at }}
                    </div>
                    <div>
                        <span style="font-weight: bold">瀏覽次數：</span>
                        {{ $post->page_view }}
                    </div>
            </div>

        </div>

    </div>
@endsection