<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'name',
        'style',
    ];
    public function links()
    {
        return $this->hasMany(Link::class);
    }
}
