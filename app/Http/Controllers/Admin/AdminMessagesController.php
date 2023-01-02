<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminMessagesController extends Controller
{
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        // All threads, ignore deleted/archived participants
//        $threads = Thread::getAllLatest()->with('messages', 'users')->get();
        // All threads that user is participating in
        $threads = Thread::forUser(Auth::id())->with('users','messages','participants')->latest('updated_at')->get();


        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        $lastThread = $threads->first();
        if ($lastThread) {
            $latestMessage = $lastThread->messages()->latest()->first();
        } else {
            $latestMessage = null;
        }
//dd($threads);
        return view('admin.messenger.index', compact('threads', 'latestMessage'));
    }


    public function show($id)
    {

        try {
            $thread = Thread::findOrFail($id);
            Auth::user()->update([
                'last_thread_id' => $id
            ]);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');
            return redirect()->route('admin.messages');
        }

        $messages = $thread->messages;

        return response()->json([
            'subject' => $thread->subject,
            'messages' => $messages->map(function ($message) {
                return [
                    'sender' => $message->user->getAttribute('name'),
                    'body' => $message->body,
                ];
            })
        ]);
    }



//    /**
//     * Shows a message thread.
//     *
//     * @param $id
//     * @return mixed
//     */
//    public function show($id)
//    {
//        try {
//            $thread = Thread::findOrFail($id);
//        } catch (ModelNotFoundException $e) {
//            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');
//
//            return redirect()->route('messages');
//        }
//
//        // show current user in list if not a current participant
//        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();
//
//        // don't show the current user in list
//        $userId = Auth::id();
//        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
//
//        $thread->markAsRead($userId);
//
//        return view('messenger.show', compact('thread', 'users'));
//    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('admin.messenger.create', compact('users'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $input = $request->all();
//        dd($request);
        foreach ($input['recipients'] as $recipient) {
            $recipients = json_decode($recipient, true);
            $id = $recipients;

            $thread = Thread::create([
                'subject' => $input['subject'],
            ]);

            // Message
            Message::create([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
                'body' => $input['message'],
            ]);

            // Sender
            Participant::create([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
                'last_read' => new Carbon(),
            ]);

            // Recipients
            if ($request->has('recipients')) {
                $thread->addParticipant($id);
            }

        }
        return redirect()->route('admin.messages');
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {

        try {
            $thread = Thread::findOrFail($id);

        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('admin.messages');
        }

        $thread->activateAllParticipants();

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->input('message'),
        ]);

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon();
        $participant->save();

        // Recipients
        if ($request->has('recipients')) {
            $thread->addParticipant($id);
        }


        return redirect()->route('admin.messages');
    }
}
