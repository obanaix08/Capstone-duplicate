<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Customer, Product, Material};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@unick.local',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password123'),
        ]);

        Customer::factory()->create([
            'name' => 'Walk-in Customer',
            'email' => 'customer@unick.local',
        ]);

        Product::factory()->create([
            'sku' => 'WD-CHAIR-STD',
            'name' => 'Standard Chair',
            'price' => 1299,
            'stock' => 25,
            'low_stock_threshold' => 10,
        ]);

        Material::factory()->create([
            'code' => 'MAT-WOOD-PLY',
            'name' => 'Plywood Sheet',
            'unit' => 'sheet',
            'stock' => 100,
            'low_stock_threshold' => 20,
        ]);
    }
}
