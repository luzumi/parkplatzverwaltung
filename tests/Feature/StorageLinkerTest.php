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

        $storageLinker = new StorageLinker();
        $this->storageLinker = $storageLinker->createStorageLink($attributes);

    }


    public function test_request_is_validated_successful()
    {
        $this->assertIsObject($this->storageLinker);
        $this->assertObjectHasAttribute('original', $this->storageLinker);
        $this->assertNotNull($this->storageLinker->original);
        $this->assertNotNull($this->storageLinker->hash);
    }


    public function testGetHash()
    {
        $this->assertIsObject($this->storageLinker);

        $this->assertEquals($this->storageLinker->
            getHash(), $this->storageLinker->hash);
    }
}
