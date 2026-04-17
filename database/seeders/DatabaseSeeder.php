<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{User, Category, MenuItem};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@foodordering.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+63 912 345 6789',
            'address' => 'Davao City',
            'is_active' => true,
        ]);

        // Create Customer
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+63 987 654 3210',
            'address' => '123 Sample St, Davao',
            'is_active' => true,
        ]);

        // Create Categories and Items
        $categories = [
            ['name' => 'Appetizers', 'description' => 'Start your meal', 'sort_order' => 1],
            ['name' => 'Main Course', 'description' => 'Main dishes', 'sort_order' => 2],
            ['name' => 'Desserts', 'description' => 'Sweet treats', 'sort_order' => 3],
            ['name' => 'Beverages', 'description' => 'Drinks', 'sort_order' => 4],
        ];

        foreach ($categories as $cat) {
            $category = Category::create($cat);

            // Add sample items
            $items = [
                ['name' => ucfirst($cat['name']).' Special', 'price' => rand(100, 300), 'is_featured' => true],
                ['name' => 'Popular '.$cat['name'], 'price' => rand(80, 250)],
            ];

            foreach ($items as $item) {
                MenuItem::create(array_merge($item, [
                    'category_id' => $category->id,
                    'description' => 'Delicious '.$item['name'],
                    'is_available' => true,
                    'preparation_time' => 15,
                ]));
            }
        }
    }
}
