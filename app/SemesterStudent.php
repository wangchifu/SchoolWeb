<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SemesterStudent extends Model
{
    protected $fillable = [
        'student_id',
        'year_class_id',
        'num',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function year_class()
    {
        return $this->belongsTo(YearClass::class);
    }
}
