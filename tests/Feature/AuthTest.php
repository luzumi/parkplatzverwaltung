<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function testLoginUser()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => $this->user->password
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    //Tests for authorized Users

    public function testAuthenticatedUserCanShowMessages()
    {
        $response = $this->actingAs($this->user)->get('/messages');
        $response->assertStatus(200);
    }

    public function testAuthenticatedUserCanAddACar()
    {
        $response = $this->actingAs($this->user)->get('/user/addCar/index');
        $response->assertStatus(200);
    }

    public function testAuthenticatedUserCanShowProfile()
    {
        $response = $this->actingAs($this->user)->get('/user/' . $this->user->id);
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    public function testAuthenticatedUserCantSeeLoginOrRegister()
    {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
    }

    public function testAuthenticatedUserCanSeeLogout()
    {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Logout');
    }

    public function testAuthenticatedUserCanLogout()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $this->actingAs($this->user)->post('/logout');
        $this->assertGuest();
    }

    public function testUnauthenticatedUserCantAddACar()
    {
        $response = $this->get('/user/addCar/index');
        $response->assertStatus(404);
    }

    public function testUnauthenticatedUserCantShowProfile()
    {
        $response = $this->get('/user/1');
        $response->assertStatus(404);
    }

    //Tests for unauthorized Users

    public function testUnauthenticatedUserCantShowMessages()
    {
        $response = $this->get('/messages');
        $response->assertStatus(500);
    }

    public function testUnauthenticatedUserCanShowParkingSpots()
    {
        $response = $this->get('/parking_spots');
        $response->assertStatus(200);
    }

    public function testUnauthenticatedUserCantShowLogin()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function testUnauthenticatedUserCantShowRegister()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'name' => 'testUser',
            'email' => 'test@test.test',
            'password' => bcrypt('password'),
            'last_thread_id' => 1
        ]);
    }

}
