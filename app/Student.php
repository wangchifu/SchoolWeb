<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'sn',
        'name',
        'sex',
        'curr_year_class_num',
        'at_school',
    ];

}
