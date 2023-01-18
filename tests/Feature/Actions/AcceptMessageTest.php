<?php

namespace Actions;

use App\Actions\AcceptMessage;
use App\Actions\CreateMessage;
use App\Enums\MessageType;
use App\Models\LogMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Tests\TestCase;

class AcceptMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createMessage = new AcceptMessage();
    }

    public function testAcceptMessage()
    {
        $logMessage = LogMessage::create([
            'user_id' => 2,
            'receiver_user_id' => 1,
            'message' => MessageType::AntwortMessage,
            'car_id' => 1,
            'parking_spot_id' => 1,
            'status' => 'open'
        ]);

        $response = $this->createMessage->acceptMessage($logMessage->id);

        $this->assertDatabaseHas('log_messages', [
            'id' => $logMessage->id,
            'status' => 'closed'
        ]);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
