<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $tab = $request->tab;

        if ($tab === 'mylist') {
            if (!auth()->check()) {
                $items = collect();
            } else {
                /** @var \App\Models\User $user */
                $user = auth()->user();

                $items = $user->likedItems()
                    ->with('purchase')
                    ->keywordSearch($keyword)
                    ->get();
            }
        } else {
            $query = Item::query()
                ->with('purchase')
                ->keywordSearch($keyword);

            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            $items = $query->get();
        }

        return view('items.index', compact('items'));
    }

    public function show($item_id)
    {
        $item = Item::with(['categories', 'comments.user'])->findOrFail($item_id);

        $likesCount = $item->likes()->count();
        $commentsCount = $item->comments()->count();

        $isLiked = false;

        if (auth()->check()) {
            $isLiked = $item->likes()
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('items.show', compact('item', 'likesCount', 'commentsCount', 'isLiked'));
    }

    public function toggleLike($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $like = $item->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $item->likes()->create([
                'user_id' => $user->id,
            ]);
        }

        return redirect()->back();
    }

    public function storeComment(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $userId = auth()->id();

        $item->comments()->create([
            'user_id' => $userId,
            'content' => $request->content,
        ]);

        return redirect()->back();
    }
}