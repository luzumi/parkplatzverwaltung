<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


         User::factory()->create([
             'name' => 'admin',
             'email' => 'admin@admin.de',
             'telefon' => fake()->phoneNumber(),
             'email_verified_at' => now(),
             'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
             'role' => 'admin',
             'remember_token' => Str::random(10),
         ]);
         ParkingSpot::factory()->create([
             'user_id' => 1,
             'number' => 1,
             'row' => 1,
             'image' => 'frei.jpg',
             'status' => 'frei',
         ]);
         Car::factory()->create([
             'user_id' => 1,
             'sign' => 'ABC DE 123',
             'manufacturer' => 'ADMIN',
             'model' => 'ISTRATOR',
             'color' => 'RED',
             'image' => 'testCar.jpg',
             'status' => true,
         ]);
    }
}
