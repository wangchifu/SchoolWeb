<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchSatisfactionClass extends Model
{
    protected $fillable = [
        'class_people',
        'q1_1',
        'q1_2',
        'q1_3',
        'q1_4',
        'q1_5',
        'q2_1',
        'q2_2',
        'q3_1',
        'q3_2',
        'q3_3',
        'q3_4',
        'q3_5',
        'q3_6',
        'q3_7',
        'q3_8',
        'q3_9',
        'q3_10',
        'q4_1',
        'q4_2',
        'favority',
        'suggest',
        'class_id',
        'user_id',
        'lunch_satisfaction_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lunch_satisfaction()
    {
        return $this->belongsTo(LunchSatisfaction::class);
    }
}
