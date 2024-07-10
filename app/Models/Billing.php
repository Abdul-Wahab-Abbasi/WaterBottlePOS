<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;
    protected $table = 'Billing';
    protected $fillable = [
        'customer_id', 'due_date', 'from_date', 'to_date', 'total', 'status'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
