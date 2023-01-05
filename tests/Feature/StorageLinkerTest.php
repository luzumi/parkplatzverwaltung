<?php

namespace Tests\Feature;

use App\Models\StorageLinker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StorageLinkerTest extends TestCase
{
    use RefreshDatabase;

    private StorageLinker $storageLinker;

    public function setUp(): void
    {
        parent::setUp();

        $attributes = [
            'filename' => 'filename',
            'extension' => 'jpg'
        ];

        $this->storageLinker = new StorageLinker($attributes);
    }


    public function testValidate()
    {
        $this->assertIsObject($this->storageLinker);
        $this->assertObjectHasAttribute('original', $this->storageLinker);
        $this->assertObjectHasAttribute('hash', $this->storageLinker);
    }

    public function testGetHash()
    {
        $this->assertIsObject($this->storageLinker);

        $this->assertEquals($this->storageLinker->
            getHash(), $this->storageLinker->hash);
    }
}
