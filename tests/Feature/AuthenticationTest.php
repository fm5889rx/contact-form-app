<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;                // データベースをリセットするトレイル

    /** @test */
    public function ログイン画面を表示できる(): void
    {
        // 実行
        $response = $this->get(route('login')); // ログイン画面を表示

        // 結果
        $response->assertStatus(200);           // HTTPステータス200を期待（正常終了）
    }

    /** @test */
    public function 正しい認証情報でログインできる(): void
    {
        // 準備
        $user = User::factory()->create([
            'password' => bcrypt('password123'),    // パスワードを指定してユーザ作成
        ]);

        // 実行
        $response = $this->post(route('login'), [   // 認証情報を渡してログイン
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // 結果
        $response->assertRedirect(route('admin.index'));    // 管理画面に遷移すること期待
        $this->assertAuthenticatedAs($user);        // 認証成功を期待
    }

    /** @test */
    public function 間違ったパスワードではログインできない(): void
    {
        // 準備
        $user = User::factory()->create([
            'password' => bcrypt('password123'),    // パスワードを指定してユーザ作成
        ]);

        // 実行
        $response = $this->post(route('login'), [   // 誤った認証情報を渡してログイン
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // 結果
        $response->assertSessionHasErrors('email'); // メールアドレスエラーが出ることを期待
        $this->assertGuest();                       // 認証されていない状態であることを期待
    }

    /** @test */
    public function 存在しないメールアドレスではログインできない(): void
    {
        // 実行
        $response = $this->post(route('login'), [   // 存在しない認証情報でログイン
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        // 結果
        $response->assertSessionHasErrors('email'); // メールアドレスエラーが出ることを期待
        $this->assertGuest();                       // 認証されていない状態であることを期待
    }

    /** @test */
    public function メールアドレスが空だとバリデーションエラーになる(): void
    {
        // 実行
        $response = $this->post(route('login'), [       // 空のメールアドレスを合してログイン
            'email' => '',
            'password' => 'password123',
        ]);

        // 結果
        $response->assertSessionHasErrors('email');  // メールアドレスエラーが出ることを期待
    }

    /** @test */
    public function パスワードが空だとバリデーションエラーになる(): void
    {
        // 準備
        $user = User::factory()->create();          // テストユーザを作成

        // 実行
        $response = $this->post(route('login'), [   // パスワードを空にしてログイン
            'email' => $user->email,
            'password' => '',
        ]);

        // 結果
        $response->assertSessionHasErrors('password');  // パスワードエラーが出ることを期待
    }
}
