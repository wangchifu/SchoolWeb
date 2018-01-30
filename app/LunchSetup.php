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
        'support_part_money',
        'support_all_money',
        'die_line',
        'place',
        'factory',
        'stud_gra_date',
        'tea_open',
        'disable',
    ];
}
