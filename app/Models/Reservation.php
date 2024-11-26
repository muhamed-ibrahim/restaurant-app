<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['from_time', 'to_time', 'user_id', 'table_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function avaliability($query, $tableId, $from, $to)
    {
        if (!$tableId || !$from || !$to) {
            return $query;
        }
        // $query->whereBetween('from', [$from, $to])
        //     ->orWhereBetween('to_time', [$from, $to])
        //     ->orWhere(function ($query) use ($request) {
        //         $query->where('from', '<', $from)
        //             ->where('to', '>', $to);
        //     });
    }
}
