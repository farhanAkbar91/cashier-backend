<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'description' => 'Berbagai macam makanan berat dan ringan'],
            ['name' => 'Minuman', 'description' => 'Aneka minuman dingin dan panas'],
            ['name' => 'Snack', 'description' => 'Makanan ringan dan camilan'],
            ['name' => 'Lainnya', 'description' => 'Produk lainnya']
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
