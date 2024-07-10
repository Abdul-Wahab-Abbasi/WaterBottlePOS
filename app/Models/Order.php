<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_no','rc_bottles','dl_bottles','total_amount','delivered_on','status'
    ];

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    // Define the relationship with Customer model
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'account_no');
    }
}
