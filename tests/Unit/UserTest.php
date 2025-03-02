<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase; // テストごとにデータベースをリセット

    /** @test */
    public function ユーザーを作成できる()
    {
        // ユーザーを1件作成
        $user = User::factory()->create([
            'name' => '山田 太郎',
            'email' => 'yamada@example.com',
        ]);

        // データベースに期待するデータがあるか確認
        $this->assertDatabaseHas('users', [
            'name' => '山田 太郎',
            'email' => 'yamada@example.com',
        ]);
    }
}
