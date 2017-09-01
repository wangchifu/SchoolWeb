<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mfile extends Model
{
    protected $fillable = [
        'name',
        'report_id',
    ];
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
