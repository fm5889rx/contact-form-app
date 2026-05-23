<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnauthenticatedRedirectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 未認証ユーザーは管理画面にアクセスするとログインページにリダイレクトされる(): void
    {
        // Act
        $response = $this->get(route('admin.index'));   // 管理画面を表示しようとする

        // Assert
        $response->assertRedirect(route('login'));      // ログイン画面にリダイレクトされることを期待
    }
}
