<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'morning_id',
        'content',
        'who_do',
        'group_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function morning()
    {
        return $this->belongsTo(Morning::class);
    }
    public function mfiles()
    {
        return $this->hasMany(Mfile::class);
    }
}
