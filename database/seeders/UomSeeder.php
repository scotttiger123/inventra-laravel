<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Uom;  // Make sure this line is added

class UomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Uom::create(['name' => 'Kilogram', 'abbreviation' => 'kg']);
    Uom::create(['name' => 'Gram', 'abbreviation' => 'g']);
    Uom::create(['name' => 'Liter', 'abbreviation' => 'l']);
    Uom::create(['name' => 'Meter', 'abbreviation' => 'm']);
    Uom::create(['name' => 'Centimeter', 'abbreviation' => 'cm']);
    Uom::create(['name' => 'Millimeter', 'abbreviation' => 'mm']);
    Uom::create(['name' => 'Piece', 'abbreviation' => 'pc']);
    Uom::create(['name' => 'Box', 'abbreviation' => 'box']);
}
}
