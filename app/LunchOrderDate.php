<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchOrderDate extends Model
{
    protected $fillable = [
        'order_date',
        'semester',
        'lunch_order_id',
    ];
    public function lunch_order()
    {
        return $this->belongsTo(LunchOrder::class);
    }
}
