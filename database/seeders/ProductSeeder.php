<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Kopi Hitam',
                'price' => 15000,
                'stock' => 50,
                'image' => null,
            ],
            [
                'name' => 'Kopi Susu',
                'price' => 20000,
                'stock' => 45,
                'image' => null,
            ],
            [
                'name' => 'Teh Manis Dingin',
                'price' => 10000,
                'stock' => 100,
                'image' => null,
            ],
            [
                'name' => 'Nasi Goreng Spesial',
                'price' => 35000,
                'stock' => 30,
                'image' => null,
            ],
            [
                'name' => 'Mie Goreng Telur',
                'price' => 25000,
                'stock' => 40,
                'image' => null,
            ],
            [
                'name' => 'Ayam Penyet',
                'price' => 30000,
                'stock' => 25,
                'image' => null,
            ],
            [
                'name' => 'Kentang Goreng',
                'price' => 20000,
                'stock' => 60,
                'image' => null,
            ],
            [
                'name' => 'Es Jeruk',
                'price' => 12000,
                'stock' => 80,
                'image' => null,
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
