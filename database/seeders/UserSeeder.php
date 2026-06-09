<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Lia Widyawati',
                'email' => 'liawidyawati037@gmail.com',
                'google_id' => '105428679991933937191',
                'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocIK6oZulWzvYLV6j2AfzMD0oC6MEALks-NLsediMd0m9uCcW2Ty=s96-c',
                'phone' => '083872041570',
                'gender' => 'Perempuan',
                'birth_place' => 'BEKASI',
                'birth_date' => '2014-01-15',
                'bio' => 'CAPRICORN',
                'address' => 'cikarang',
                'role' => 'user',
                'two_factor_code' => '873123',
                'two_factor_expires_at' => '2026-05-22 07:56:37',
                'created_at' => '2026-05-16 13:46:53',
                'updated_at' => '2026-05-22 07:46:37',
            ],
            [
                'id' => 2,
                'name' => 'Admin',
                'email' => 'admin@ebook.com',
                'password' => '123456',
                'role' => 'admin',
                'created_at' => '2026-05-16 13:51:53',
                'updated_at' => '2026-05-23 04:01:05',
            ],
            [
                'id' => 3,
                'name' => 'John Doe',
                'email' => 'reinalddyaldy183@gmail.com',
                'password' => '123456',
                'role' => 'user',
                'created_at' => '2026-05-16 13:57:08',
                'updated_at' => '2026-05-16 13:57:08',
            ],
            [
                'id' => 4,
                'name' => 'LIA WIDYAWATI',
                'email' => 'liawidyawati03@gmail.com',
                'google_id' => '105710018643897975853',
                'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocKN4lVaX0_DIM2MSdk3FVQ4T6pRCeNq7aGOjHdqaSVWcFx3Fg=s96-c',
                'bio' => 'swit.luv',
                'role' => 'user',
                'created_at' => '2026-05-16 16:19:57',
                'updated_at' => '2026-05-22 04:01:31',
            ],
            [
                'id' => 5,
                'name' => 'LIA WIDYAWATI',
                'email' => 'liawdy03@gmail.com',
                'google_id' => '110777566079658775630',
                'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocLO5W3WVZ6H8byop6kJone-PlZ0gaE693nfH1_ncAt5Tcx37g=s96-c',
                'role' => 'user',
                'two_factor_code' => '433170',
                'two_factor_expires_at' => '2026-05-21 03:05:31',
                'created_at' => '2026-05-20 17:05:00',
                'updated_at' => '2026-05-21 02:55:31',
            ],
            [
                'id' => 6,
                'name' => 'Lia Widyawati',
                'email' => 'lialiul2004@gmail.com',
                'google_id' => '109810342209993559335',
                'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocLvTF44RvEHudlLS7H8cra1XlkKCv9HouSBJ1jrpD7mzpYdJmD2=s96-c',
                'role' => 'user',
                'created_at' => '2026-05-22 04:06:14',
                'updated_at' => '2026-05-23 04:00:03',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['id' => $userData['id']], $userData);
        }
    }
}
