<?php

namespace App\Models;

use App\Pfile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'who_do',
        'page_view',
        'published_at',
        'unpublished_at',
        'category_id',
        'user_id',
        'insite',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pfiles()
    {
        return $this->hasMany(Pfile::class);
    }

}
