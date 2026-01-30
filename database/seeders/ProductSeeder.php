<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем по 5 товаров для каждой категории
        $categories = Category::all();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'name' => "Товар {$i} категории {$category->name}",
                    'description' => "Описание товара {$i} для категории {$category->name}",
                    'price' => rand(100, 10000) / 100, // случайная цена от 1.00 до 100.00
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
