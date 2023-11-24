<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'series_id',
        'distance_code',
        'photo',
        'name',
        'description',
        'short_name',
        'height_mm',
        'height_inch',
        'packaging',
        'euro_palet',
        'price',
        'discount', 
    ];
}