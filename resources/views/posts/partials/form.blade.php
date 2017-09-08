<div class="form-group">
    <label for="insite">校內文件請打勾：</label>
    <?php $checked=($post->insite==1)?"checked=checked":""; ?>
    <div>
    <input name="insite" type="checkbox" value="1" style="zoom:200%" {{ $checked }}>
    </div>
</div>
<div class="form-group">
    <label for="category_id">分類*：</label>
    <?php
        $c = substr(auth()->user()->order_by,1,1);
    ?>
    {{ Form::select('category_id', $categories, $c, ['id' => 'category_id', 'class' => 'form-control', 'placeholder' => '請選擇分類']) }}
</div>

<div class="form-group">
    <label for="title">標題*：</label>
    {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => '請輸入標題']) }}
</div>

<div class="form-group">
    <label for="content">內文*：</label>
    {{ Form::textarea('content', null, ['id' => 'content', 'class' => 'form-control', 'rows' => 10, 'placeholder' => '請輸入內容']) }}
</div>

<div class="form-group">
    <?php
        $week = [
            '1'=>'一',
            '2'=>'二',
            '3'=>'三',
            '4'=>'四',
            '5'=>'五',
            '6'=>'六',
            '0'=>'日',
        ];
        $today=date('Y-m-d-');
        $today .= $week[date('w')];
    ?>
    <label for="published_at">上架時間：</label>
    <script src="{{ asset('js/cal/jscal2.js') }}"></script>
    <script src="{{ asset('js/cal/lang/cn.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/jscal2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/border-radius.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/steel/steel.css') }}">
    <input id="published_at" name="published_at" class="form-control" placeholder ="請輸入上架時間" value="{{ $today }}">
    <script>
        Calendar.setup({
            dateFormat : '%Y-%m-%d-%A',
            inputField : 'published_at',
            trigger    : 'published_at',
            onSelect   : function() { this.hide();}
        });
    </script>
</div>

<div class="form-group">
    <label for="unpublished_at">下架時間：</label>
    <script src="{{ asset('js/cal/jscal2.js') }}"></script>
    <script src="{{ asset('js/cal/lang/cn.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/jscal2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/border-radius.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cal/steel/steel.css') }}">
    <input id="unpublished_at" name="unpublished_at" class="form-control" placeholder ="請輸入上架時間">
    <script>
        Calendar.setup({
            dateFormat : '%Y-%m-%d-%A',
            inputField : 'unpublished_at',
            trigger    : 'unpublished_at',
            onSelect   : function() { this.hide();}
        });
    </script>
</div>