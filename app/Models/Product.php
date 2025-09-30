<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class,'order_products')
        ->withPivot('qty', 'price')
        ->withTimestamps();
    }
}
