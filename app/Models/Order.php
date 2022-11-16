<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'id_costumer', 'id_car', 'pickup_time', 'rental_time', 'status', 'start_rental', 'end_rental', 'price', 'return_code'
    ];
}
