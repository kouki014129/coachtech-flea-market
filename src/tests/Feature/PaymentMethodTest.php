<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_card_payment_method_is_reflected_on_purchase_page(): void
    {
        $buyer = User::create([
            'name' => '購入者',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password123'),
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市',
            'building' => 'テストマンション',
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

        $response = $this->actingAs($buyer)
            ->withSession([
                '_old_input' => [
                    'payment_method' => 'card',
                ],
            ])
            ->get('/purchase/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('カード支払い');
    }

    public function test_convenience_store_payment_method_is_reflected_on_purchase_page(): void
    {
        $buyer = User::create([
            'name' => '購入者2',
            'email' => 'buyer2@example.com',
            'password' => bcrypt('password123'),
            'postal_code' => '123-4567',
            'address' => '大阪府大阪市',
            'building' => 'テストマンション',
        ]);

        $buyer->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $seller = User::create([
            'name' => '出品者2',
            'email' => 'seller2@example.com',
            'password' => bcrypt('password123'),
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'name' => 'HDD',
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'price' => 5000,
            'image' => 'items/hdd.jpg',
            'condition' => '良好',
        ]);

        $response = $this->actingAs($buyer)
            ->withSession([
                '_old_input' => [
                    'payment_method' => 'convenience_store',
                ],
            ])
            ->get('/purchase/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('コンビニ支払い');
    }
}