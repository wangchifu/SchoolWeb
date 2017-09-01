<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'title',
        'link',
        'block_id',
    ];
    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
