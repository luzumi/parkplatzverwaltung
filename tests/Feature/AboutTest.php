<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AboutTest extends TestCase
{
    use RefreshDatabase;

    public function testAboutSiteIsReachable()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }

    public function testAboutSiteGetTitle()
    {
        $title = 'About us - Parkplatzverwaltung';
        $response = $this->get('/about');

        $response->assertViewHas("viewData", function ($viewData) use ($title) {
            return collect($viewData)->contains(function ($value, $key) use ($title) {
                return strcasecmp($value, $title) === 0;
            });
        });
    }

    public function testAboutSiteShowText()
    {
        $response = $this->get('/about');

        $response->assertSee('Willkommen bei Laravel-Parkplatzvermietung,');
        $response->assertSee('Sie sich keine Sorgen über das Parken machen');
        $response->assertSee('genießen Sie die Stadt in vollen Zügen!');
        $response->assertSee('Developed by: luzumi');
    }
}
