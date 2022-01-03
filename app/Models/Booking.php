<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable=[
        'customer_id',
        'package_id',
        'additional_notes',
        'status',
        'reference_id',
        'start_date',
        'end_date',
        'is_deleted'
    ];
}
