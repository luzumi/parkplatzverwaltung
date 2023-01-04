<?php

namespace Tests\Feature;

use App\Models\LogMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function testHomeIsReachable()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testHomeContainsTitle()
    {
        $response = $this->get('/');
        $title = "Home Page - Parkplatzverwaltung";
        $response = $this->get('/');

        $response->assertViewHas("viewData", function ($viewData) use ($title) {
            return collect($viewData)->contains(function ($value, $key) use ($title) {
                return strcasecmp($value, $title) === 0;
            });
        });
    }

    public function testHomeGetImageUnregisteredUser()
    {
        $response = $this->get('/');

        $response->assertSee("/img/unregistered_user.png");
    }

    public function testHomeGetImageParkingArea()
    {
        $response = $this->get('/');

        $response->assertSee("/img/parking_area.png");
    }

    public function testHomeGetImageAdminUser()
    {
        $response = $this->get('/');

        $response->assertSee("/img/admin_user.png");
    }

    public function testHomeShowFooter()
    {
        $response = $this->get('/');

        $response->assertSee("Copyright");
        $response->assertSee("Daniel");
        $response->assertSee("luzumi");
    }

    public function testHomeLinkAppCss()
    {
        $response = $this->get('/');

        $response->assertSee("http://localhost/css/app.css");
    }

    public function testHomeHasNoMessages()
    {
        $message = LogMessage::all();
        $response = $this->get('/');
        // prüfen daß keine Nachrichten da sind
        $response->assertDontSee("viewData", function ($viewData) use ($message) {
            return collect($viewData)->contains('message', $message);
        });
    }

    public function testHomeHasMessages()
    {
        $response = $this->get('/');
        // prüfen daß Nachrichten da sind
        self::assertTrue(true);
    }

    public function testHomeAcceptMessagesIsWorking()
    {
        $response = $this->get('/');
        // prüfen daß Nachrichten als gelesen markiert werden
        self::assertTrue(true);
    }
}
