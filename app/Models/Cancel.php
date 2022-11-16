<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancel extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_order', 'canceling_person', 'reason'
    ];
}
