<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Factory as FactoryFacade;
use Tests\TestCase;

class limitTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('seckill/10089');
        $response->assertStatus(200);
        for ($i = 0; $i < 100; $i++) {
            $response = $this->actingAs($user)->post('seckill/10089');
            if( $response->status(429) == 429){
                echo $i;
            }
//            echo $response->status(429); // 断言返回429状态码，表示请求过多
        }

    }
}
