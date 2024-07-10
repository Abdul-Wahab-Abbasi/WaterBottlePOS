<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['start_date', 'party_name','phone', 'address'];
    public function Billing()
    {
        return $this->hasMany(Billing::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'account_no');
    }
}
