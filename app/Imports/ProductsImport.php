<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        if (!isset($row['series'])) {
            return null;
        }

        return new Product([
            'series' => $row['series'],
            'type' => $row['type'],
            'distance_code' => $row['distance_code'],
            'name' => $row['name'],
            'short_name' => $row['short_name'],
            'height_mm' => $row['height_mm'],
            'height_inch' => $row['height_inch'],
            'packaging' => $row['packaging'],
            'euro_palet' => $row['euro_palet'],
            'price_net' => $row['price_net'],
        ]);
    }
}
