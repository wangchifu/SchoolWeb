<div class="text-right">
    @can('update', $morning)
    <a class="btn btn-info btn-xs" href="{{ url('/mornings/').'/'.$morning->id.'/edit' }}" role="button"><span class="glyphicon glyphicon-pencil"></span> 修改名稱</a>
    @endcan
    @can('delete', $morning)
    {{ Form::open(['route' => ['mornings.destroy', $morning->id], 'method' => 'DELETE', 'style' => 'display: inline-block']) }}
        <button type="submit" class="btn btn-danger btn-xs" role="button" onclick="return confirm('是否確定刪除此會議？看清楚喔！');"><span class="glyphicon glyphicon-trash"></span> 刪除</button>
    {{ Form::close() }}
    @endcan
</div>