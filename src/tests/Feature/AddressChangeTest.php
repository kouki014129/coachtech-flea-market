<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_updated_address_is_reflected_on_purchase_page(): void
    {
        $buyer = User::create([
            'name' => '購入者',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password123'),
            'postal_code' => '111-1111',
            'address' => '大阪府大阪市北区',
            'building' => '元の建物',
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

        $this->actingAs($buyer)->put('/purchase/address/' . $item->id, [
            'postal_code' => '222-2222',
            'address' => '大阪府大阪市中央区',
            'building' => '変更後の建物',
        ]);

        $response = $this->actingAs($buyer)->get('/purchase/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('222-2222');
        $response->assertSee('大阪府大阪市中央区');
        $response->assertSee('変更後の建物');
    }
}