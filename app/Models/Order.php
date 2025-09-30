<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Order extends Model
{
    protected $fillable = [
        'total',
        'status',
        'claim_date',
        'user_id',
    ];

    protected $casts = [
         'claim_date' => 'date',
    ];

    public function products(){
        return $this->belongsToMany(Product::class,'order_products')
        ->withPivot('qty', 'price')
        ->withTimestamps();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
