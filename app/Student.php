<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'sn',
        'name',
        'sex',
        'at_school',
    ];
}
