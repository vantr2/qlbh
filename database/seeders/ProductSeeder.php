<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Product;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Product::whereNotNull('_id')->delete();
        Product::factory()->count(100)->create();
    }
}
