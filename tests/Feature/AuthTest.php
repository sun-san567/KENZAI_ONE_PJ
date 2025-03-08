<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\BuildingMaterialGenreSeeder::class);
    }

    public function test_正しい情報でログインできる()
    {
        // ✅ シーダーで作成済みのユーザーを取得
        $user = User::where('email', 'yamada@kenzai-corp.co.jp')->first();
        $this->assertNotNull($user, 'シーダーでユーザーが作成されていることを確認');

        // ✅ APIリクエストとして送信
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // ✅ レスポンスが200であることを確認し、ユーザーが認証されていることを検証
        $response->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', 'ログインに成功しました')
                    ->etc()
            );

        $this->assertAuthenticated();
    }

    public function test_間違ったパスワードではログインできない()
    {
        // ✅ シーダーで作成済みのユーザーを取得
        $user = User::where('email', 'yamada@kenzai-corp.co.jp')->first();
        $this->assertNotNull($user, 'シーダーでユーザーが作成されていることを確認');

        // ✅ APIリクエストとして送信
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        // ✅ 401（未認証）が返ることを確認
        $response->assertStatus(401)
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->where('message', '認証に失敗しました')
                    ->etc()
            );

        $this->assertGuest();
    }
}
