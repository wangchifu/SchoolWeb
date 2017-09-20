<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'order',
        'title',
        'description',
        'type',
        'test_id',
        'content',
    ];
    public function question()
    {
        return $this->belongsTo('App\Question')->withDefault();
    }
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
