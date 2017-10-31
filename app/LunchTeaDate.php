<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchTeaDate extends Model
{
    protected $fillable = [
        'order_date',
        'semester',
        'lunch_order_id',
        'user_id',
    ];
}
