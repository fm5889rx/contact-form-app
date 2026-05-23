<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 登録画面を表示できる(): void
    {
        // 実行
        $response = $this->get(route('register'));      // 登録画面を表示

        // 結果
        $response->assertStatus(200);                   // HTTPステータス200を期待（正常系）
    }

    /** @test */
    public function 新規ユーザーを登録できる(): void
    {
        // 実行
        $response = $this->post(route('register'), [    // ダミーユーザを登録
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 結果
        $response->assertRedirect(route('admin.index'));    //　管理画面にリダイレクトされることを期待
        $this->assertDatabaseHas('users', [     // データベースに保存されていることを期待
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
        $this->assertAuthenticated();           // 新規ユーザが認証させていることを期待
    }

    /** @test */
    public function 名前が空だとバリデーションエラーになる(): void
    {
        // 実行
        $response = $this->post(route('register'), [    // 名前が空のダミーユーザを登録
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 結果
        $response->assertSessionHasErrors('name');      // 名前エラーが出ることを期待
    }

    /** @test */
    public function メールアドレスが空だとバリデーションエラーになる(): void
    {
        // 実行
        $response = $this->post(route('register'), [    // メールアドレスが空のダミーユーザを登録
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 結果
        $response->assertSessionHasErrors('email');     // メールアドレスエラーが出ることを期待
    }

    /** @test */
    public function 無効なメールアドレス形式だとバリデーションエラーになる(): void
    {
        // 実行
        $response = $this->post(route('register'), [    // 無効なメールアドレスのユーザを登録
            'name' => 'テストユーザー',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 結果
        $response->assertSessionHasErrors('email');     // メールアドレスエラーが出ることを期待
    }

    /** @test */
    public function 既に登録済みのメールアドレスだとバリデーションエラーになる(): void
    {
        // 準備
        User::factory()->create(['email' => 'existing@example.com']);   // 指定メールアドレスのユーザを追加

        // 実行
        $response = $this->post(route('register'), [    // 登録済みメールアドレスのダミーユーザを登録しようとする
            'name' => 'テストユーザー',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 結果
        $response->assertSessionHasErrors('email');     // メールアドレスエラーが出ることを期待
    }

    /** @test */
    public function パスワードが8文字未満だとバリデーションエラーになる(): void
    {
        // 実行
        $response = $this->post(route('register'), [    // パスワードが8文字未満のダミーユーザを登録しようりする
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        // 結果
        $response->assertSessionHasErrors('password');  // パスワードエラーが出ることを期待
    }

    /** @test */
    public function パスワード確認が一致しないとバリデーションエラーになる(): void
    {
        // 実行
        $response = $this->post(route('register'), [    // パスワード確認が一致しないユーザを登録しようとする
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        // 結果
        $response->assertSessionHasErrors('password');  //パスワードエラーが出ることを期待
    }
}
