<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchSatisfaction extends Model
{
    protected $fillable = [
        'semester',
        'name',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
