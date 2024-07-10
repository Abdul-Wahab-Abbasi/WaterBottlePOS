<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class OrderProduct extends Model
{
    protected $table = 'order_product';
    use HasFactory;
    protected $fillable = [
        'order_id', 'product_id','product_name','size','product_price','qty'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
