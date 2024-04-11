<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accesories extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'short_name',
        'photo',
        'height_mm',
        'height_inch',
        'packaging',
        'euro_palet',
        'price_net',
        'wood_width',
        'pieces_in_m2',
        'for_type',
        'name_for_client',
        'fits_to_system'
    ];
}