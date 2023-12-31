<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'series',
        'type',
        'distance_code',
        'photo',
        'name',
        'short_name',
        'height_mm',
        'height_inch',
        'euro_palet',
        'packaging',
        'price_net',
    ];
}
