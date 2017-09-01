@can('update', $post)
<a class="btn btn-primary btn-xs" href="{{ route('posts.edit', $post->id) }}" role="button">編輯</a>
@endcan
@can('delete', $post)
{{ Form::open(['route' => ['posts.destroy', $post->id], 'method' => 'DELETE', 'style' => 'display: inline-block']) }}
    <button type="submit" class="btn btn-danger btn-xs" role="button" onclick="return confirm('是否確定刪除？看清楚喔！');">刪除</button>
{{ Form::close() }}
@endcan