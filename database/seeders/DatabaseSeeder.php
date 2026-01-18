<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories (safe to re-run)
        $categories = [
            ['name' => 'Phones', 'description' => 'Mobile phones and smartphones'],
            ['name' => 'Computing', 'description' => 'Computers, laptops, and computing devices'],
            ['name' => 'Cameras', 'description' => 'Cameras, action cams, and photography gear'],
            ['name' => 'Drones', 'description' => 'Drones and aerial accessories'],
            ['name' => 'Audio', 'description' => 'Headphones, earbuds, speakers, and audio gear'],
            ['name' => 'Gaming', 'description' => 'Gaming devices and peripherals'],
            ['name' => 'Wearables', 'description' => 'Smartwatches and wearable tech'],
            ['name' => 'Smart Home', 'description' => 'Smart home devices and automation'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }

        // Create admin user
        User::updateOrCreate(
            ['email' => 'delicanok133@gmail.com'],
            [
                'first_name' => 'Kenneth',
                'last_name' => 'Delicano',
                'password' => Hash::make('123123'),
                'contact' => '+1234567890',
                'address' => '123 Main Street',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create customer user
        User::updateOrCreate(
            ['email' => 'chai123@gmail.com'],
            [
                'first_name' => 'alaaaaaa',
                'last_name' => 'guilleena',
                'password' => Hash::make('123123123'),
                'contact' => '+0987654321',
                'address' => '456 Oak Avenue',
                'role' => 'customer',
            ]
        );

        // Create sample products
        $products = [
            [
                'product_name' => 'iPhone 15 Pro Max',
                'description' => 'Latest Apple flagship phone with advanced camera system',
                'price' => 119999,
                'image_path' => 'images/iPhone_15_Pro_Max_Blue_Titanium.jpg',
                'stock' => 50,
            ],
            [
                'product_name' => 'iPhone 15 Pro',
                'description' => 'Premium iPhone with Pro features',
                'price' => 99999,
                'image_path' => 'images/15 PR.jpg',
                'stock' => 40,
            ],
            [
                'product_name' => 'iPhone 15',
                'description' => 'Standard iPhone 15 model',
                'price' => 79999,
                'image_path' => 'images/15.jpg',
                'stock' => 60,
            ],
            [
                'product_name' => 'Logitech G304',
                'description' => 'Lightweight gaming mouse with excellent tracking',
                'price' => 2999,
                'image_path' => 'images/LOGITECH-G304-LIGHTSPEED-WIRELES.jpg',
                'stock' => 100,
            ],
            [
                'product_name' => 'RK61 Keyboard',
                'description' => 'Mechanical keyboard with RGB lighting',
                'price' => 4999,
                'image_path' => 'images/RK61_-1.jpg',
                'stock' => 75,
            ],
            [
                'product_name' => 'Ergonomic Chair',
                'description' => 'Comfortable office chair for long work sessions',
                'price' => 12999,
                'image_path' => 'images/princess-chair-12.jpg',
                'stock' => 30,
            ],
            [
                'product_name' => 'Standing Desk',
                'description' => 'Adjustable height standing desk',
                'price' => 24999,
                'image_path' => 'images/product desk.jpg',
                'stock' => 25,
            ],
            [
                'product_name' => 'Desk Lamp',
                'description' => 'LED desk lamp with adjustable brightness',
                'price' => 1999,
                'image_path' => 'images/Lamp.jpg',
                'stock' => 80,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['product_name' => $product['product_name']],
                $product
            );
        }

        // Seed Philippines address reference data (required for region/province/city/barangay dropdowns)
        // Safe to rerun: each seeder checks if table already has rows.
        $this->call([
            PhilippineRegionSeeder::class,
            PhilippineProvinceSeeder::class,
            PhilippineCitySeeder::class,
            PhilippineBarangaySeeder::class,
        ]);
    }
}
