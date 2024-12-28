<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Electronic',
                'slug' => Str::slug('Electronic'),
                'meta_title' => 'Best Electronics',
                'meta_description' => 'Discover top-quality electronics.',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Furniture',
                'slug' => Str::slug('Furniture'),
                'meta_title' => 'Stylish Furniture',
                'meta_description' => 'Elegant and modern furniture.',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fashion',
                'slug' => Str::slug('Fashion'),
                'meta_title' => 'Trendy Fashion',
                'meta_description' => 'Stay stylish with the latest trends.',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sports',
                'slug' => Str::slug('Sports'),
                'meta_title' => 'Sports Gear',
                'meta_description' => 'Gear up for your next adventure.',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Books',
                'slug' => Str::slug('Books'),
                'meta_title' => 'World of Books',
                'meta_description' => 'Explore books from all genres.',
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
          DB::table('departments')->insert($departments);
    }
}
