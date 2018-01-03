<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchCheck extends Model
{
    protected $fillable = [
        'order_date',
        'main_eat',
        'main_vag',
        'co_vag',
        'vag',
        'soup',
        'reason',
        'action',
        'semester',
        'class_id',
        'user_id',
    ];
}
