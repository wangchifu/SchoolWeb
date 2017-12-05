<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchStuDate extends Model
{
    protected $fillable = [
        'order_date',
        'enable',
        'semester',
        'lunch_order_id',
        'student_id',
        'class_id',
        'p_id',
        'eat_style',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
