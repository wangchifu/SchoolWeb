<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YearClass extends Model
{
    protected $fillable = [
        'semester',
        'year_class',
        'name',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
