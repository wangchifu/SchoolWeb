<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchStuOrder extends Model
{
    protected $fillable = [
        'semester',
        'student_id',
        'student_num',
        'p_id',
        'out_in',
        'eat_style',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
