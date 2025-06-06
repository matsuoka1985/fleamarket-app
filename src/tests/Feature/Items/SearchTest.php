<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * 商品検索機能について、「商品名」で部分一致検索ができる
     */
    public function it_can_search_items_by_partial_title_match()
    {
        // テストユーザ作成・ログイン（自分の商品は除外される仕様対応）
        $user = User::factory()->create();
        $this->actingAs($user);

        // 他人の商品（ヒットする商品）
        Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Super Nintendo',
        ]);

        // 他人の商品（ヒットしない商品）
        Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'title' => 'PlayStation',
        ]);

        // 検索実行（"Nintendo" で部分一致）
        $response = $this->get(route('items.index', ['keyword' => 'Nintendo']));

        $response->assertStatus(200);
        $response->assertSee('Super Nintendo');
        $response->assertDontSee('PlayStation');
    }
}
