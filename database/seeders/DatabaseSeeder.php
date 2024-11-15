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

        DB::table('wishlists')->delete();
        DB::table('shipments')->delete();
        DB::table('shippers')->delete();
        DB::table('cities')->delete();
        DB::table('provinces')->delete();
        DB::table('payments')->delete();
        DB::table('order_details')->delete();
        DB::table('orders')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::table('users')->delete();

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
            ['user_id' => 1, 'order_date' => now()->subDays(1), 'status' => 'completed', 'total_price' => 100],
            ['user_id' => 2, 'order_date' => now(), 'status' => 'pending', 'total_price' => 50],
            ['user_id' => 3, 'order_date' => now(), 'status' => 'pending', 'total_price' => 60],
            ['user_id' => 4, 'order_date' => now()->subDays(2), 'status' => 'completed', 'total_price' => 70],
            ['user_id' => 1, 'order_date' => now()->subDays(3), 'status' => 'declined', 'total_price' => 80],
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

        // $table->foreignId('order_id')->constrained(table: 'orders', column: 'id')->cascadeOnDelete();
        //     $table->foreignId('user_id')->constrained(table: 'users', column: 'id')->cascadeOnDelete();
        //     $table->enum('payment_method', ['Credit Card', 'PayPal', 'Bank Transfer', 'Cash']);
        //     $table->decimal('amount', 10, 2);
        //     $table->enum('payment_status', ['Paid', 'Pending', 'Failed', 'Refunded']);
        $payments = [
            ['order_id' => 1, 'user_id' => 1, 'payment_method' => 'Credit Card', 'amount' => 100, 'payment_status' => 'Paid'],
            ['order_id' => 2, 'user_id' => 2, 'payment_method' => 'PayPal', 'amount' => 50, 'payment_status' => 'Pending'],
            ['order_id' => 3, 'user_id' => 3, 'payment_method' => 'Bank Transfer', 'amount' => 60, 'payment_status' => 'Pending'],
            ['order_id' => 4, 'user_id' => 4, 'payment_method' => 'Cash', 'amount' => 70, 'payment_status' => 'Paid'],
            ['order_id' => 5, 'user_id' => 1, 'payment_method' => 'Credit Card', 'amount' => 80, 'payment_status' => 'Failed'],
        ];

        DB::table('payments')->insert($payments);

        $json = file_get_contents(database_path('seeders/provinces.json'));
        $data = json_decode($json, true);
        $provinces = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }, $data);
        // DB::table('provinces')->insert(['id' => 1, 'name' => 'Unknown']);
        DB::table('provinces')->insert($provinces);

        $json = file_get_contents(database_path('seeders/regencies.json'));
        $data = json_decode($json, true);
        $data = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'province_id' => $item['province_id'],
            ];
        }, $data);
        // DB::table('cities')->insert(['id' => 1, 'name' => 'Unknown', 'province_id' => 1]);
        DB::table('cities')->insert($data);

        $shipper = [
            ['name' => 'FedEx'],
            ['name' => 'UPS'],
            ['name' => 'DHL'],
            ['name' => 'USPS'],
            ['name' => 'Royal Mail'],
        ];

        DB::table('shippers')->insert($shipper);

        // $table->foreignId('order_id')->constrained();
        //     $table->string('shipping_address');
        //     $table->string('tracking_number')->nullable();
        //     $table->dateTime('estimated_delivery_date')->nullable();
        //     $table->dateTime('actual_delivery_date')->nullable();
        // $table->foreignId('shipper_id')->constrained('shippers')->onDelete('cascade');
        $shipment = [
            ['order_id' => 1, 'shipping_address' => '123 Main St', 'tracking_number' => '123456', 'estimated_delivery_date' => now()->addDays(5), 'actual_delivery_date' => now()->addDays(5), 'shipper_id' => 1],
            ['order_id' => 2, 'shipping_address' => '456 Elm St', 'tracking_number' => '456789', 'estimated_delivery_date' => now()->addDays(3), 'actual_delivery_date' => now()->addDays(3), 'shipper_id' => 2],
            ['order_id' => 3, 'shipping_address' => '789 Oak St', 'tracking_number' => '789012', 'estimated_delivery_date' => now()->addDays(4), 'actual_delivery_date' => now()->addDays(4), 'shipper_id' => 3],
            ['order_id' => 4, 'shipping_address' => '012 Pine St', 'tracking_number' => '012345', 'estimated_delivery_date' => now()->addDays(6), 'actual_delivery_date' => now()->addDays(6), 'shipper_id' => 2],
            ['order_id' => 5, 'shipping_address' => '345 Cedar St', 'tracking_number' => '345678', 'estimated_delivery_date' => now()->addDays(7), 'actual_delivery_date' => now()->addDays(7), 'shipper_id' => 4],
        ];

        DB::table('shipments')->insert($shipment);

        $wishlist = [
            ['user_id' => 1, 'product_id' => 1],
            ['user_id' => 2, 'product_id' => 2],
            ['user_id' => 3, 'product_id' => 3],
            ['user_id' => 4, 'product_id' => 4],
            ['user_id' => 1, 'product_id' => 5],
            ['user_id' => 2, 'product_id' => 1],
            ['user_id' => 3, 'product_id' => 2],
            ['user_id' => 4, 'product_id' => 3],
        ];

        DB::table('wishlists')->insert($wishlist);
    }
}
