<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchSetup extends Model
{
    protected $fillable = [
        'semester',
        'tea_money',
        'stud_money',
        'stud_back_money',
        'die_line',
        'stud_gra_date',
    ];
}
