<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SellTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_item_with_required_information(): void
    {
        $user = User::create([
            'name' => '出品ユーザー',
            'email' => 'seller@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $category1 = Category::create([
            'name' => '家電',
        ]);

        $category2 = Category::create([
            'name' => 'パソコン',
        ]);

        $response = $this->actingAs($user)->post('/sell', [
            'name' => 'ノートPC',
            'brand_name' => 'TechBrand',
            'description' => '高性能なノートパソコン',
            'price' => 45000,
            'condition' => '良好',
            'categories' => [$category1->id, $category2->id],
            'image' => UploadedFile::fake()->image('item.jpeg'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'ノートPC',
            'brand_name' => 'TechBrand',
            'description' => '高性能なノートパソコン',
            'price' => 45000,
            'condition' => '良好',
        ]);
    }
}