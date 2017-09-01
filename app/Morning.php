<?php

namespace App;
use App\User;
use App\Morning;
use Illuminate\Database\Eloquent\Model;

class Morning extends Model
{
    protected $fillable = [
        'name','user_id','who_do',
    ];
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
