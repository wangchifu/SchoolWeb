<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'answer',
        'user_id',
        'question_id',
        'test_id',
    ];
    public function answer()
    {
        return $this->belongsTo('App\Answer')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
