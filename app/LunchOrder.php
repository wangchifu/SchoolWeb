<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchOrder extends Model
{
    protected $fillable = [
        'name',
        'semester',
        'enable',
    ];
    public function lunch_order_dates()
    {
        return $this->hasMany(LunchOrderDate::class);
    }
}
