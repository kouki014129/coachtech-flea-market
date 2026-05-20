<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    public function create()
    {
        $categories = Category::all();

        return view('sells.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validated();

        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'brand_name' => $data['brand_name'] ?? null,
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $imagePath,
            'condition' => $data['condition'],
        ]);

        $item->categories()->attach($data['categories']);

        return redirect('/');
    }
}
