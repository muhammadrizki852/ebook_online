<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EbookSeeder::class,
            PurchaseSeeder::class,
            FavoriteSeeder::class,
            TransactionActivitySeeder::class,
        ]);
    }
}
