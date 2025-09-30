<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\History;

class ProductHistory extends Model
{
    protected $fillable = [
        'name',
        'price',
        'qty',
        'subtotal',
        'history_id',
    ];

    public function product(){
        return $this->belongsTo(History::class);
    }
}
