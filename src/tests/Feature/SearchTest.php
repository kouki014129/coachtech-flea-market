<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_items_can_be_searched_by_partial_name_match(): void
    {
        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
        ]);

        Item::create([
            'user_id' => $seller->id,
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'image' => 'items/watch.jpg',
            'condition' => '良好',
        ]);

        Item::create([
            'user_id' => $seller->id,
            'name' => 'ノートPC',
            'brand_name' => 'TechBrand',
            'description' => '高性能なノートパソコン',
            'price' => 45000,
            'image' => 'items/laptop.jpg',
            'condition' => '良好',
        ]);

        $response = $this->get('/?keyword=時計');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('ノートPC');
    }

    public function test_search_keyword_is_preserved_in_mylist(): void
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
            'name' => '赤い時計',
            'brand_name' => 'BrandA',
            'description' => '赤い腕時計',
            'price' => 1000,
            'image' => 'items/red-watch.jpg',
            'condition' => '良好',
        ]);

        Item::create([
            'user_id' => $seller->id,
            'name' => '青いバッグ',
            'brand_name' => 'BrandB',
            'description' => '青いバッグ',
            'price' => 2000,
            'image' => 'items/blue-bag.jpg',
            'condition' => '良好',
        ]);

        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=時計');

        $response->assertStatus(200);
        $response->assertSee('value="時計"', false);
    }
}