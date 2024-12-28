<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Electronics Department
            [
                'name' => 'Mobile Phones',
                'department_id' => 1, // Replace with the actual `id` of the Electronics department
                'parent_id' => null, // Top-level category
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartphones',
                'department_id' => 1,
                'parent_id' => 1, // Child of Mobile Phones
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laptops',
                'department_id' => 1,
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Furniture Department
            [
                'name' => 'Living Room Furniture',
                'department_id' => 2, // Replace with the actual `id` of the Furniture department
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sofas',
                'department_id' => 2,
                'parent_id' => 4, // Child of Living Room Furniture
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Fashion Department
            [
                'name' => 'Men’s Clothing',
                'department_id' => 3, // Replace with the actual `id` of the Fashion department
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shirts',
                'department_id' => 3,
                'parent_id' => 6, // Child of Men’s Clothing
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
