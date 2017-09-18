<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fix extends Model
{
    protected $fillable = [
        'fun_id',
        'user_id',
        'title',
        'content',
        'reply',
        'done',
    ];
    public function fix()
    {
        return $this->belongsTo('App\Fix')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function fun()
    {
        return $this->belongsTo(Fun::class);
    }
}
