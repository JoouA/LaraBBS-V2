<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Participant;
use App\Models\Thread;
use Auth;
use Carbon\Carbon;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $threads = Thread::participateBy(Auth::id());
        if (Auth::user()->newThreadsCount() == 0) {
            Auth::user()->message_count = 0;
            Auth::user()->save();
        }
        return view('messages.index', compact('threads'));
    }

    public function show($id)
    {
        // 两个人之间的对应是一个thread


        $thread = Thread::findOrFail($id);
        // 获得两个人对话中的另外一个用户
        $participant = $thread->participant();

        // 获得两个人之间的对话消息
        $messages = $thread->messages->sortByDesc('created_at');

        // counters  当前thread中还没有读的消息的数量
        $unread_message_count = $thread->userUnreadMessagesCount(Auth::id());


        if ($unread_message_count > 0) {
            Auth::user()->message_count -= $unread_message_count;
            Auth::user()->save();
        }

        // 将thread中当前用户的消息标志位已读
        $thread->markAsRead(Auth::id());

        return view('messages.show', compact('thread', 'participant', 'messages', 'unread_message_count'));
    }


    public function create(User $user)
    {
        $recipient = $user;

        $thread = Thread::between([$recipient->id, Auth::id()])->first();
        if ($thread) {
            return redirect()->route('messages.show', $thread->id);
        }

        return view('messages.create', compact('recipient'));
    }

    public function store(Request $request)
    {
        $recipient = User::findOrFail($request->recipient_id);

        if ($request->thread_id) {
            $thread = Thread::findOrFail($request->thread_id);
        } else {
            $subject = Auth::user()->name . ' 给 ' . $recipient->name . ' 的私信。';
            $thread = Thread::create(['subject' => $subject]);
        }

        // Message
        $message = $request->message;
        Message::create(['thread_id' => $thread->id, 'user_id' => Auth::id(), 'body' => $message]);

        // Sender
        $participant = Participant::firstOrCreate(['thread_id' => $thread->id, 'user_id' => Auth::id()]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipient
        $thread->addParticipant($recipient->id);

        /*// Notify user by Email
        $job = (new SendNotifyMail('new_message', Auth::user(), $recipient, null, null, $message))
            ->delay(config('phphub.notify_delay'));
        dispatch($job);*/

        // notifications count
        $recipient->message_count++;
        $recipient->save();

        return redirect()->route('messages.show', $thread->id);
    }
}
