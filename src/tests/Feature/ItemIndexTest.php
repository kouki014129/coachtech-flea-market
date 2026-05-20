<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed(): void
    {
        $user1 = User::create([
            'name' => '出品者1',
            'email' => 'seller1@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user2 = User::create([
            'name' => '出品者2',
            'email' => 'seller2@example.com',
            'password' => bcrypt('password123'),
        ]);

        Item::create([
            'user_id' => $user1->id,
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'image' => 'items/test-watch.jpg',
            'condition' => '良好',
        ]);

        Item::create([
            'user_id' => $user2->id,
            'name' => 'HDD',
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => 5000,
            'image' => 'items/test-hdd.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('HDD');
    }

    public function test_sold_label_is_displayed_for_purchased_items(): void
    {
        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
        ]);

        $buyer = User::create([
            'name' => '購入者',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password123'),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'image' => 'items/test-watch.jpg',
            'condition' => '良好',
        ]);

        \App\Models\Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市',
            'building' => 'テストビル',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_own_items_are_not_displayed_for_logged_in_user(): void
    {
        $loginUser = User::create([
            'name' => 'ログインユーザー',
            'email' => 'loginuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $otherUser = User::create([
            'name' => '他ユーザー',
            'email' => 'otheruser@example.com',
            'password' => bcrypt('password123'),
        ]);

        Item::create([
            'user_id' => $loginUser->id,
            'name' => '自分の商品',
            'brand_name' => 'MyBrand',
            'description' => '自分が出品した商品',
            'price' => 1000,
            'image' => 'items/my-item.jpg',
            'condition' => '良好',
        ]);

        Item::create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
            'brand_name' => 'OtherBrand',
            'description' => '他人が出品した商品',
            'price' => 2000,
            'image' => 'items/other-item.jpg',
            'condition' => '良好',
        ]);

        $response = $this->actingAs($loginUser)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }
}