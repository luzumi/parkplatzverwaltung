<?php

namespace App\Actions;

use App\Http\Requests\CarRequest;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateNewCarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->password = $this->faker->password;

        $this->admin = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($this->password),
            'role' => 'admin',
            'last_thread_id' => 1
        ]);

        $this->user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($this->password),
            'role' => 'client',
            'last_thread_id' => 1
        ]);

        $this->carRequest = new CarRequest([
            'sign' => 'asadas',
            'manufacturer' => 'company',
            'model' => 'name',
            'color' => 'colorName',
        ]);
        $this->createNewCar = new CreateNewCar();
    }

    public function testHandle()
    {
        $this->actingAs($this->user);
        $setImageName = new SetImageName();
        $creatMessage = new CreateMessage();
        $request = new CarRequest([
            'user_id' => $this->user->id,
            'sign' => 'asadas',
            'manufacturer' => 'company',
            'model' => 'name',
            'color' => 'colorName',
        ]);
        $file = UploadedFile::fake()->create('image.jpg', 500, 'image/jpeg');
        $request->files->add(['image' => $file]);

        $car = $this->createNewCar->handle($request, $setImageName, $creatMessage);

        $this->assertDatabaseHas('cars', [
            'user_id' => $this->user->id,
            'sign' => 'asadas',
            'manufacturer' => 'company',
            'model' => 'name',
            'color' => 'colorName',
        ]);
        $this->assertEquals($car->user_id, $this->user->id);
        $this->assertEquals($car->sign, 'asadas');
        $this->assertEquals($car->manufacturer, 'company');
        $this->assertEquals($car->model, 'name');
        $this->assertEquals($car->color, 'colorName');
        $this->assertTrue($car->status);
        Storage::disk('public/media')->assertExists($car->image);
    }
}
