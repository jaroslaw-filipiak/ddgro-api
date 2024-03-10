<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'total_area',
        'count',
        'gap_between_slabs',
        'lowest',
        'highest',
        'terrace_thickness',
        'distance_between_joists',
        'distance_between_supports',
        'joist_height',
        'slab_width',
        'slab_height',
        'slab_thickness',
        'tiles_per_row',
        'sum_of_tiles',
        'support_type',
        'main_system',
        'name_surname',
        'email',
        'proffesion',
        'terms_accepted',
        'slabs_count',
        'supports_count',
        'products',
        'accesories',
        'additional_accessories',
        'm_standard',
        'm_spiral',
        'm_max',
        'm_alu'
    ];
}