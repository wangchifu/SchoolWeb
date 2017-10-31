<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchStuDate extends Model
{
    protected $fillable = [
        'order_date',
        'semester',
        'lunch_order_id',
        'student_id',
    ];
}
