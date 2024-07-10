<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'date', 'time', 'customer_id', 'admin', 'order_id', 'total', 'received', 'change_returned'
    ];

    // Define relationships if needed
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
