<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_an_item_and_like_count_increases(): void
    {
        $user = User::create([
            'name' => 'ログインユーザー',
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->forceFill([
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

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/like');

        $response->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->fresh()->likes()->count());
    }

    public function test_liked_icon_changes_color_for_liked_item(): void
    {
        $user = User::create([
            'name' => 'ログインユーザー',
            'email' => 'login2@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller2@example.com',
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

        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('ハートロゴ_ピンク.png', false);
        $response->assertDontSee('ハートロゴ_デフォルト.png', false);
    }

    public function test_user_can_unlike_an_item_and_like_count_decreases(): void
    {
        $user = User::create([
            'name' => 'ログインユーザー',
            'email' => 'login3@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $seller = User::create([
            'name' => '出品者',
            'email' => 'seller3@example.com',
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

        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/like');

        $response->assertRedirect();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(0, $item->fresh()->likes()->count());
    }
}