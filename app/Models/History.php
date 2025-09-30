<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductHistory;

class History extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_contact',
        'customer_gender',
        'customer_birthday',
        'customer_address',
        'total',
        'claim_date',
        'status',
        'staff_id',
        'staff_name',
        'staff_email',
    ];

    protected $casts =[
        'claim_date' => 'date',
        'customer_birthday' => 'date',
    ];
    public function products(){
        return $this->hasMany(ProductHistory::class);
    }
    
}
