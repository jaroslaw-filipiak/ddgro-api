<?php

namespace App\Imports;

use App\Models\Accesories;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccesoriesImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new Accesories([
            'code' => $row['code'],
            'name' => $row['name'],
            'short_name' => $row['short_name'],
            'photo' => $row['photo'], 'height_mm' => $row['height_mm'],
            'height_inch' => $row['height_inch'],
            'packaging' => $row['packaging'],
            'euro_palet' => $row['euro_palet'],
            'price_net' => $row['price_net'],
            'wood_width' => $row['wood_width'],
            'pieces_in_m2' => $row['pieces_in_m2'],

        ]);
    }
}
