<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SemesterStudent extends Model
{
    protected $fillable = [
        'student_id',
        'YearClass_id',
        'num',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function yearClass()
    {
        return $this->belongsTo(YearClass::class);
    }
}
