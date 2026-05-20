<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PurchaseSuccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_is_completed_and_saved_after_success(): void
    {
        $buyer = User::create([
            'name' => '購入者',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password123'),
        ]);

        $buyer->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'image' => 'items/watch.jpg',
            'condition' => '良好',
        ]);

        $fakeSession = (object) [
            'payment_status' => 'paid',
            'metadata' => (object) [
                'user_id' => $buyer->id,
                'item_id' => $item->id,
                'payment_method' => 'card',
                'postal_code' => '123-4567',
                'address' => '大阪府大阪市',
                'building' => 'テストマンション',
            ],
        ];

        $mock = Mockery::mock(PurchaseService::class);
        $mock->shouldReceive('retrieveCheckoutSession')
            ->once()
            ->with('test_session_id')
            ->andReturn($fakeSession);

        $this->app->instance(PurchaseService::class, $mock);

        $response = $this->actingAs($buyer)
            ->get('/purchase/success/' . $item->id . '?session_id=test_session_id');

        $response->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市',
            'building' => 'テストマンション',
        ]);
    }

    public function test_purchased_item_is_displayed_as_sold_on_item_index(): void
    {
        $buyer = User::create([
            'name' => '購入者2',
            'email' => 'buyer2@example.com',
            'password' => bcrypt('password123'),
        ]);

        $seller = User::create([
            'name' => '出品者2',
            'email' => 'seller2@example.com',
            'password' => bcrypt('password123'),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入済み腕時計',
            'brand_name' => 'Rolax',
            'description' => '購入済み商品',
            'price' => 15000,
            'image' => 'items/watch.jpg',
            'condition' => '良好',
        ]);

        \App\Models\Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市',
            'building' => 'テストマンション',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('購入済み腕時計');
        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_displayed_on_profile_buy_page(): void
    {
        $buyer = User::create([
            'name' => '購入者3',
            'email' => 'buyer3@example.com',
            'password' => bcrypt('password123'),
        ]);

        $buyer->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $seller = User::create([
            'name' => '出品者3',
            'email' => 'seller3@example.com',
            'password' => bcrypt('password123'),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => '購入済みノートPC',
            'brand_name' => 'TechBrand',
            'description' => '購入済み商品',
            'price' => 45000,
            'image' => 'items/laptop.jpg',
            'condition' => '良好',
        ]);

        \App\Models\Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市',
            'building' => 'テストマンション',
        ]);

        $response = $this->actingAs($buyer)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('購入済みノートPC');
    }
}