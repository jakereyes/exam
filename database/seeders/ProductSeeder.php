<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = [
            ['name' => 'shoes', 'quantity' => 99],
            ['name' => 'short', 'quantity' => 50],
        ];

        DB::table('products')->insert($product);
    }
}
