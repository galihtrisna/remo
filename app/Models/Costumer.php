<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user', 'phone_number', 'address', 'date_of_birth', 'driving_license'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
