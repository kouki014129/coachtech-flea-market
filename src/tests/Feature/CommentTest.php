<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_post_comment(): void
    {
        $user = User::create([
            'name' => 'コメント投稿者',
            'email' => 'commenter@example.com',
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

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comment', [
            'content' => 'とても良い商品です',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'とても良い商品です',
        ]);

        $this->assertEquals(1, $item->fresh()->comments()->count());
    }

    public function test_guest_cannot_post_comment(): void
    {
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

        $response = $this->post('/item/' . $item->id . '/comment', [
            'content' => 'ゲストコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => 'ゲストコメント',
        ]);
    }

    public function test_comment_is_required(): void
    {
        $user = User::create([
            'name' => 'コメント投稿者2',
            'email' => 'commenter2@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $seller = User::create([
            'name' => '出品者3',
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

        $response = $this->actingAs($user)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'content' => '',
            ]);

        $response->assertRedirect('/item/' . $item->id);
        $response->assertSessionHasErrors('content');
    }

    public function test_comment_cannot_exceed_255_characters(): void
    {
        $user = User::create([
            'name' => 'コメント投稿者3',
            'email' => 'commenter3@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $seller = User::create([
            'name' => '出品者4',
            'email' => 'seller4@example.com',
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

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'content' => $longComment,
            ]);

        $response->assertRedirect('/item/' . $item->id);
        $response->assertSessionHasErrors('content');
    }
}