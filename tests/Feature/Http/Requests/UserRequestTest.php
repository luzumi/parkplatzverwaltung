<?php

namespace Http\Requests;

use App\Enums\SampleRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'testUser',
            'email' => 'test@test.de',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'last_thread_id' => 1
        ]);
    }

    public function testRegisterWithNoErrors()
    {
        $data = [
            'name' => SampleRequest::Only6Letters->value,
            'email' => SampleRequest::ValidEmail->value,
            'password' => SampleRequest::ValidPassword->value,
            'password_confirmation' => SampleRequest::ValidPassword->value,
        ];

        $response = $this->post('/register', $data);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasNoErrors('name');
        $response->assertSessionHasNoErrors('email');
        $response->assertSessionHasNoErrors('password');
        $response->assertSessionHasNoErrors('password_confirmation');
    }

    public function testRegisterWithEmptyEmail()
    {
        $data = [
            'name' => SampleRequest::Only6Letters->value,
            'email' => SampleRequest::NoSigns->value,
            'password' => SampleRequest::ValidPassword->value,
            'password_confirmation' => SampleRequest::ValidPassword->value,
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors('email');
    }

    public function testRegisterWithEmptyName()
    {
        $data = [
            'name' => SampleRequest::NoSigns->value,
            'email' => SampleRequest::ValidEmail->value,
            'password' => SampleRequest::ValidPassword->value,
            'password_confirmation' => SampleRequest::ValidPassword->value,
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors('name');
    }

    public function testRegisterWithInvalidName()
    {
        $data = [
            'name' => SampleRequest::LongText300Sign->value,
            'email' => SampleRequest::ValidEmail->value,
            'password' => SampleRequest::ValidPassword->value,
            'password_confirmation' => SampleRequest::ValidPassword->value,
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors('name');
    }

    public function testRegisterWithInvalidEmail()
    {
        $data = [
            'name' => SampleRequest::Only6Letters->value,
            'email' => SampleRequest::LongText300Sign->value,
            'password' => SampleRequest::ValidPassword->value,
            'password_confirmation' => SampleRequest::ValidPassword->value,
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors('email');
    }

    public function testRegisterWithInvalidPassword()
    {
        $data = [
            'name' => SampleRequest::Only6Letters->value,
            'email' => SampleRequest::ValidEmail->value,
            'password' => SampleRequest::LongText300Sign->value,
            'password_confirmation' => SampleRequest::LongText300Sign->value,
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors('password');
    }

    public function testRegisterWithInvalidSigns()
    {
        $data = [
            'name' => SampleRequest::SqlInject->value,
            'email' => SampleRequest::SignWithSymbols->value,
            'password' => SampleRequest::SignWithSymbols->value,
            'password_confirmation' => SampleRequest::SqlInject->value,
        ];

        $response = $this->post('/register', $data);

        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors('name');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('password');
    }
}
