<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'active',
        'name',
        'do',
        'user_id',
        'unpublished_at',
    ];
    public function test()
    {
        return $this->belongsTo('App\Test')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
