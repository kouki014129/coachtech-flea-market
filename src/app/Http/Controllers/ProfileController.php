<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $sellItems = $user->items;

        $buyItems = $user->purchases()
            ->with('item')
            ->get()
            ->pluck('item');

        return view('profiles.index', compact('user', 'sellItems', 'buyItems'));
    }

    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('profiles.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $imagePath;
        }

        $user->update([
            'name' => $data['name'],
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
            'building' => $data['building'] ?? null,
            'profile_image' => $data['profile_image'] ?? $user->profile_image,
        ]);

        return redirect('/');
    }
}