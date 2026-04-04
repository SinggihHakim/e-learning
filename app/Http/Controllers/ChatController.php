<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        // Get unique users that this user has chatted with, ordered by latest message
        $chatPartners = User::whereHas('messagesSent', function($q) use ($userId) {
                $q->where('receiver_id', $userId);
            })
            ->orWhereHas('messagesReceived', function($q) use ($userId) {
                $q->where('sender_id', $userId);
            })
            ->get();
            
        // Fallback: If no chats, show all users from same courses
        if ($chatPartners->isEmpty()) {
            if (auth()->user()->isStudent()) {
                 $courseIds = auth()->user()->enrolledCourses()->pluck('courses.id');
                 $chatPartners = User::whereHas('courses', function($q) use ($courseIds) {
                     $q->whereIn('courses.id', $courseIds);
                 })->orWhereHas('enrolledCourses', function($q) use ($courseIds) {
                     $q->whereIn('courses.id', $courseIds);
                 })->where('id', '!=', $userId)->distinct()->get();
            } else if (auth()->user()->isTeacher()) {
                 $courseIds = auth()->user()->courses()->pluck('id');
                 $chatPartners = User::whereHas('enrolledCourses', function($q) use ($courseIds) {
                     $q->whereIn('courses.id', $courseIds);
                 })->where('id', '!=', $userId)->distinct()->get();
            }
        }

        $activeUserId = $request->query('user');
        $activeUser = null;
        $messages = [];

        if ($activeUserId) {
            $activeUser = User::find($activeUserId);
            if ($activeUser) {
                // Mark messages as read
                Message::where('sender_id', $activeUserId)
                       ->where('receiver_id', $userId)
                       ->where('is_read', false)
                       ->update(['is_read' => true]);

                $messages = Message::where(function($q) use ($userId, $activeUserId) {
                        $q->where('sender_id', $userId)->where('receiver_id', $activeUserId);
                    })
                    ->orWhere(function($q) use ($userId, $activeUserId) {
                        $q->where('sender_id', $activeUserId)->where('receiver_id', $userId);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('chat.index', compact('chatPartners', 'activeUser', 'messages'));
    }

    public function fetchMessages(User $user)
    {
        $userId = auth()->id();
        
        Message::where('sender_id', $user->id)
               ->where('receiver_id', $userId)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        $messages = Message::where(function($q) use ($userId, $user) {
                $q->where('sender_id', $userId)->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($userId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);
        
        return response()->json(['success' => true, 'message' => $message]);
    }
}
