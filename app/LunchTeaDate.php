<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchTeaDate extends Model
{
    protected $fillable = [
        'order_date',
        'enable',
        'semester',
        'lunch_order_id',
        'user_id',
        'place',
        'factory',
        'eat_style',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
