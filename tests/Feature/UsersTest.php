<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function testUserIsReachable()
    {
        $user = User::create([
            'name' => 'name',
            'email' => 'test@test.test',
            'password' => 'test'
        ]);

        $response = $this->actingAs($user)->get('/user/');

        $response->assertStatus(200);
        $response->assertSee('name');
        $response->assertViewHas("viewData", function ($viewData) use ($user) {
            return $viewData['users']->contains($user);
        });
    }
}
