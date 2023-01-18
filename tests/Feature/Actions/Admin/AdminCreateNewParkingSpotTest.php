<?php

namespace Actions\Admin;

use App\Actions\Admin\AdminCreateNewParkingSpot;
use App\Actions\CreateMessage;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\ParkingSpot;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreateNewParkingSpotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $faker = Faker::create();
        $password = $faker->password;
        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt($password),
            'role' => 'client',
            'last_thread_id' => 1
        ]);
        $admin = User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt($password),
            'role' => 'admin',
            'last_thread_id' => 1
        ]);

        for ($i = 0; $i < 8; $i++){
            ParkingSpot::create([
                'user_id' => $admin->id,
                'number' => $i + 1,
                'row' => intdiv($i, 4) + 1,
                'image' => 'frei.jpg',
                'status' => 'frei'
            ]);
        }
    }

    public function testHandle()
    {
        $adminCreateNewParkingSpot = new AdminCreateNewParkingSpot();
        $request = new ParkingSpotRequest(['status' => 'frei']);
        $createMessage = new CreateMessage();

        $newParkingSpot = $adminCreateNewParkingSpot->handle($request, $createMessage);

        $this->assertEquals(9, $newParkingSpot->id);
        $this->assertEquals(9, $newParkingSpot->number);
        $this->assertEquals(3, $newParkingSpot->row);
        $this->assertEquals('frei.jpg', $newParkingSpot->image);
        $this->assertEquals('frei', $newParkingSpot->status);
        $this->assertEquals(ParkingSpot::class, $newParkingSpot::class);
    }
}
