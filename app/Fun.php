<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fun extends Model
{
    protected $fillable = [
        'type',
        'name',
        'username',
    ];
    public function fun()
    {
        return $this->belongsTo('App\Fun')->withDefault();
    }
    public function fixes()
    {
        return $this->hasMany(Fix::class);
    }
}
