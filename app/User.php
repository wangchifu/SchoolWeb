<?php

namespace App;

use App\Models\Post;
use App\Morning;
use App\Report;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unactive',
        'name',
        'email',
        'website',
        'username',
        'password',
        'job_title',
        'group_id',
        'group_id2',
        'order_by',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function mornings()
    {
        return $this->hasMany(Morning::class);
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    public function fixs()
    {
        return $this->hasMany(Fix::class);
    }
    public function tests()
    {
        return $this->hasMany(Test::class);
    }
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function year_class()
    {
        return $this->hasOne(YearClass::class);
    }
}
