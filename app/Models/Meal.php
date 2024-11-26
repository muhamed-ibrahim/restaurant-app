<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    protected $fillable = ['price', 'description', 'quantity_available', 'discount'];

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
