<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'active',
        'name',
        'openSections',
        'closeSections',
    ];
    public function classroom()
    {
        return $this->belongsTo('App\Classroom')->withDefault();
    }
}
