<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Электроника', 'description' => 'Техника и гаджеты'],
            ['name' => 'Одежда', 'description' => 'Одежда и аксессуары'],
            ['name' => 'Книги', 'description' => 'Литература всех жанров'],
            ['name' => 'Мебель', 'description' => 'Домашняя и офисная мебель'],
            ['name' => 'Спорт', 'description' => 'Спортивные товары'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
