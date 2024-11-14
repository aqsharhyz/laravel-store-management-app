<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->delete();
        DB::table('categories')->delete();
        DB::table('products')->delete();
        DB::table('orders')->delete();
        DB::table('order_details')->delete();
        DB::table('payments')->delete();

        User::create([
            'name' => 'admin',
            'email' => 'a@g.com',
            'is_admin' => true,
            'password' => bcrypt('s'),
        ]);

        User::factory(10)->create();

        $category = [
            ['name' => 'Electronics', 'description' => 'Electronics'],
            ['name' => 'Clothing', 'description' => 'Clothing'],
            ['name' => 'Books', 'description' => 'Books'],
            ['name' => 'Toys', 'description' => 'Toys'],
            ['name' => 'Furniture', 'description' => 'Furniture'],
        ];

        DB::table('categories')->insert($category);

        $products = [
            ['name' => 'Laptop', 'description' => 'A laptop', 'sku' => 'LAP001', 'price' => 1000, 'stock' => 100, 'status' => 'active', 'category_id' => 1],
            ['name' => 'T-shirt', 'description' => 'A t-shirt', 'sku' => 'TS001', 'price' => 20, 'stock' => 1000, 'status' => 'active', 'category_id' => 2],
            ['name' => 'Book', 'description' => 'A book', 'sku' => 'BK001', 'price' => 10, 'stock' => 1000, 'status' => 'active', 'category_id' => 3],
            ['name' => 'Toy', 'description' => 'A toy', 'sku' => 'TOY001', 'price' => 5, 'stock' => 1000, 'status' => 'active', 'category_id' => 4],
            ['name' => 'Table', 'description' => 'A table', 'sku' => 'TAB001', 'price' => 50, 'stock' => 100, 'status' => 'active', 'category_id' => 5],
        ];

        DB::table('products')->insert($products);

        $orders = [
            ['user_id' => 1, 'order_date' => now(), 'status' => 'pending', 'total_price' => 40],
            ['user_id' => 2, 'order_date' => now(), 'status' => 'pending', 'total_price' => 50],
            ['user_id' => 3, 'order_date' => now(), 'status' => 'pending', 'total_price' => 60],
            ['user_id' => 4, 'order_date' => now(), 'status' => 'pending', 'total_price' => 70],
            ['user_id' => 5, 'order_date' => now(), 'status' => 'pending', 'total_price' => 80],
        ];

        DB::table('orders')->insert($orders);

        $orderDetails = [
            ['order_id' => 1, 'product_id' => 1, 'quantity' => 1, 'price_at_purchase' => 1000],
            ['order_id' => 2, 'product_id' => 2, 'quantity' => 2, 'price_at_purchase' => 20],
            ['order_id' => 3, 'product_id' => 3, 'quantity' => 3, 'price_at_purchase' => 10],
            ['order_id' => 1, 'product_id' => 4, 'quantity' => 4, 'price_at_purchase' => 5],
            ['order_id' => 2, 'product_id' => 5, 'quantity' => 5, 'price_at_purchase' => 50],
        ];

        DB::table('order_details')->insert($orderDetails);

        $payments = [];

        DB::table('payments')->insert($payments);
    }
}
