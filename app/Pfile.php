<?php

namespace App;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class Pfile extends Model
{
    protected $fillable = [
        'name',
        'post_id',
    ];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
