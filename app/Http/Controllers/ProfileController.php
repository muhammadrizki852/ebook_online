<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', 'string', 'max:30'],
            'birth_place' => ['nullable', 'string', 'max:120'],
            'birth_date' => ['nullable', 'date'],
            'bio' => ['nullable', 'string', 'max:500'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name = $data['name'];
        $user->phone = $data['phone'] ?? null;
        $user->gender = $data['gender'] ?? null;
        $user->birth_place = $data['birth_place'] ?? null;
        $user->birth_date = $data['birth_date'] ?? null;
        $user->bio = $data['bio'] ?? null;
        $user->address = $data['address'] ?? null;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http://') && !str_starts_with($user->avatar, 'https://')) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->fresh()->avatar_url,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'birth_place' => $user->birth_place,
                'birth_date' => optional($user->birth_date)->format('Y-m-d'),
                'bio' => $user->bio,
                'address' => $user->address,
            ],
        ]);
    }
}
