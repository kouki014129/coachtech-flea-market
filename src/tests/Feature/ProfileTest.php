<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_displays_user_information_and_selling_items(): void
    {
        $user = User::create([
            'name' => 'プロフィールユーザー',
            'email' => 'profile@example.com',
            'password' => bcrypt('password123'),
            'profile_image' => 'profiles/test-user.png',
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        Item::create([
            'user_id' => $user->id,
            'name' => '出品商品',
            'brand_name' => 'BrandA',
            'description' => '自分の出品商品',
            'price' => 1000,
            'image' => 'items/selling-item.jpg',
            'condition' => '良好',
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('プロフィールユーザー');
        $response->assertSee('出品商品');
        $response->assertSee('profiles/test-user.png', false);
    }

    public function test_profile_page_displays_purchased_items_on_buy_tab(): void
    {
        $user = User::create([
            'name' => 'プロフィールユーザー',
            'email' => 'profile@example.com',
            'password' => bcrypt('password123'),
            'profile_image' => 'profiles/test-user.png',
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $otherSeller = User::create([
            'name' => '他の出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
        ]);

        $purchasedItem = Item::create([
            'user_id' => $otherSeller->id,
            'name' => '購入商品',
            'brand_name' => 'BrandB',
            'description' => '自分が購入した商品',
            'price' => 2000,
            'image' => 'items/purchased-item.jpg',
            'condition' => '良好',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市',
            'building' => 'テストビル',
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入商品');
    }

    public function test_profile_edit_page_displays_initial_values(): void
    {
        $user = User::create([
            'name' => '初期値ユーザー',
            'email' => 'initial@example.com',
            'password' => bcrypt('password123'),
            'profile_image' => 'profiles/initial-user.png',
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市北区',
            'building' => '初期ビル',
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('value="初期値ユーザー"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('大阪府大阪市北区');
        $response->assertSee('初期ビル');
        $response->assertSee('profiles/initial-user.png', false);
    }
}