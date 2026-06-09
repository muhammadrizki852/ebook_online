<?php

namespace Database\Seeders;

use App\Models\Favorite;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $favorites = [
            ['id' => 2, 'user_id' => 1, 'ebook_id' => 1, 'created_at' => '2026-05-16 14:43:10', 'updated_at' => '2026-05-16 14:43:10'],
            ['id' => 4, 'user_id' => 4, 'ebook_id' => 9, 'created_at' => '2026-05-16 16:42:25', 'updated_at' => '2026-05-16 16:42:25'],
            ['id' => 8, 'user_id' => 1, 'ebook_id' => 9, 'created_at' => '2026-05-17 03:29:03', 'updated_at' => '2026-05-17 03:29:03'],
            ['id' => 9, 'user_id' => 5, 'ebook_id' => 9, 'created_at' => '2026-05-20 17:46:21', 'updated_at' => '2026-05-20 17:46:21'],
            ['id' => 10, 'user_id' => 5, 'ebook_id' => 1, 'created_at' => '2026-05-20 17:46:29', 'updated_at' => '2026-05-20 17:46:29'],
        ];

        foreach ($favorites as $favoriteData) {
            Favorite::updateOrCreate(['id' => $favoriteData['id']], $favoriteData);
        }
    }
}
