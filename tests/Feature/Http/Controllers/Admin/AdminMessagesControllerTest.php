<?php

namespace Http\Controllers\Admin;

use App\Models\User;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMessagesControllerTest extends TestCase
{
    use RefreshDatabase;
    use HasFactory;

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

        $this->thread = Thread::create([
            'subject' => 'thread'
        ]);

        $this->message = Message::create([
            'thread_id' => $this->thread->id,
            'user_id' => $this->user->id,
            'body' => 'message'
        ]);

        $this->paticipant = Participant::create([
            'thread_id' => $this->thread->id,
            'user_id' => $this->admin->id,
            'last_read' => null
        ]);

    }

    public function testIndex()
    {

        $response = $this->actingAs($this->admin)->get(route('admin.messages'));

        $response->assertStatus(200);
        $response->assertSee($this->thread->subject);
        $response->assertSee($this->message->body);
        $response->assertSee($this->user->image);
        $this->assertEquals($this->message->body, $response->original->latestMessage->body);
    }

    public function testStore()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.messages.store'), [
            'recipients' => array($this->user->id),
            'subject' => 'storeSubject',
            'message' => 'answer',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('admin/messages');
        $this->assertDatabaseHas('messenger_threads', [
            'subject' => 'storeSubject'
        ]);
        $this->assertDatabaseHas('messenger_messages', [
            'thread_id' => $this->thread->id + 1,
            'user_id' => $this->admin->id,
            'body' => 'answer'
        ]);
        $this->assertDatabaseHas('messenger_participants', [
            'thread_id' => $this->thread->id + 1,
            'user_id' => $this->admin->id,
        ]);
    }

    public function testCreate()
    {
        User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($this->password),
            'role' => 'client',
            'last_thread_id' => 1
        ]);
        $users = User::where('id', '!=', $this->admin->id)->get();

        $response = $this->actingAs($this->admin)->get(route('admin.messages.create'));

        $response->assertStatus(200);
        foreach ($users as $user)  $response->assertSee($user->name);

    }

    public function testUpdate()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.messages.update', $this->thread->id), [
                'thread_id' => $this->thread->id,
                'message' => 'answer'
            ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.messages'));
        $this->assertDatabaseHas('messenger_threads', [
            'subject' => $this->thread->subject
        ]);
        $this->assertDatabaseHas('messenger_messages', [
            'thread_id' => $this->thread->id,
            'user_id' => $this->admin->id,
            'body' => 'answer'
        ]);
        $this->assertDatabaseHas('messenger_participants', [
            'thread_id' => $this->thread->id,
            'user_id' => $this->admin->id,
        ]);
    }

    public function testShow()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.messages.show', $this->thread->id));

        $response->assertStatus(200);
        $response->assertJson([
            'subject' => $this->thread->subject,
            'messages' => [
                [
                    'sender' => $this->message->user->getAttribute('name'),
                    'body' => $this->message->body,
                ]
            ]
        ]);

    }

    public function testShowWithWrongId()
    {
        $wrongId = 0;

        $response = $this->actingAs($this->admin)->get(route('admin.messages.show', $wrongId));
        $response->assertSessionHas("error_message", "The thread with ID: $wrongId was not found.");

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.messages'));
    }

    public function testUpdateWithWrongID()
    {
        $wrongId = 0;

        $response = $this->actingAs($this->admin)
            ->put(route('admin.messages.update', $wrongId), [
                'subject' => $this->thread->subject,
                'thread_id' => $this->thread->id,
                'message' => 'answer'
            ]);
        $response->assertSessionHas("error_message", "The thread with ID: $wrongId was not found.");

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.messages'));
    }
}
