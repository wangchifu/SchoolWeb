<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderClassroom extends Model
{
    protected $fillable = [
        'classroom_id',
        'orderDate',
        'section',
        'user_id',
    ];
    public function OrderClassroom()
    {
        return $this->belongsTo('App\OrderClassroom')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
