<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_liked_items_are_displayed_in_mylist(): void
    {
        $user = User::create([
            'name' => 'ログインユーザー',
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
        ]);

        $likedItem = Item::create([
            'user_id' => $seller->id,
            'name' => 'いいねした商品',
            'brand_name' => 'BrandA',
            'description' => 'いいね対象の商品',
            'price' => 1000,
            'image' => 'items/liked-item.jpg',
            'condition' => '良好',
        ]);

        $notLikedItem = Item::create([
            'user_id' => $seller->id,
            'name' => 'いいねしていない商品',
            'brand_name' => 'BrandB',
            'description' => '未いいねの商品',
            'price' => 2000,
            'image' => 'items/not-liked-item.jpg',
            'condition' => '良好',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_sold_label_is_displayed_for_purchased_items_in_mylist(): void
    {
        $user = User::create([
            'name' => 'ログインユーザー',
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
        ]);

        $likedItem = Item::create([
            'user_id' => $seller->id,
            'name' => '購入済みのいいね商品',
            'brand_name' => 'BrandA',
            'description' => '購入済みの商品',
            'price' => 3000,
            'image' => 'items/purchased-liked-item.jpg',
            'condition' => '良好',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市',
            'building' => 'テストビル',
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_no_items_are_displayed_in_mylist_for_guests(): void
    {
        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
        ]);

        Item::create([
            'user_id' => $seller->id,
            'name' => 'ゲストには表示されない商品',
            'brand_name' => 'BrandA',
            'description' => '未認証ユーザーには表示されない',
            'price' => 1000,
            'image' => 'items/guest-hidden-item.jpg',
            'condition' => '良好',
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('ゲストには表示されない商品');
    }
}