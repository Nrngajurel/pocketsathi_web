<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $helpData = [
            [
                'name' => 'Technical Support',
                'subcategories' => [
                    ['name' => 'Software Installation'],
                    ['name' => 'Hardware Troubleshooting'],
                    ['name' => 'Internet Connectivity'],
                    ['name' => 'Printer Setup'],
                ],
            ],
            [
                'name' => 'General Assistance',
                'subcategories' => [
                    ['name' => 'Moving Assistance'],
                    ['name' => 'Pet Care'],
                    ['name' => 'Language Translation'],
                    ['name' => 'Grocery Shopping'],
                ],
            ],
            [
                'name' => 'Healthcare',
                'subcategories' => [
                    ['name' => 'Medical Advice'],
                    ['name' => 'First Aid'],
                    ['name' => 'Mental Health Support'],
                    ['name' => 'Physical Therapy'],
                ],
            ],
            [
                'name' => 'Emergency',
                'subcategories' => [
                    ['name' => 'Medical Emergency'],
                    ['name' => 'Fire Emergency'],
                    ['name' => 'Natural Disaster'],
                    ['name' => 'Security Threat'],
                ],
            ],
        ];

        foreach ($helpData as $categoryData) {
            $category = Category::create(['name' => $categoryData['name']]);
            $category->subcategories()->createMany($categoryData['subcategories']);
        }
    }
}
