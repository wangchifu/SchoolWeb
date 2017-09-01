<div class="form-group">
    <div>
    <label for="title">會議日期：</label>
    </div>
    <div class="col-md-3">
        {{ Form::select('year', $year,$this_day[0], ['id' => 'year', 'class' => 'form-control',]) }}
    </div>
    <div class="col-md-3">
        {{ Form::select('month', $month,$this_day[1], ['id' => 'month', 'class' => 'form-control',]) }}
    </div>
    <div class="col-md-3">
        {{ Form::select('day', $day,$this_day[2], ['id' => 'day', 'class' => 'form-control',]) }}
    </div>
    <div class="col-md-3">
        {{ Form::select('week', $week,$this_day[3], ['id' => 'week', 'class' => 'form-control',]) }}
    </div>
</div>
<br><br>
<div class="form-group">
    <div>
    <label for="name">會議名稱：</label>
    </div>
    <div>
    {{ Form::select('name', ["教師晨會"=>"1_教師晨會","校務會議"=>"2_校務會議"], $this_day[4], ['id' => 'name', 'class' => 'form-control',]) }}
    </div>
</div>