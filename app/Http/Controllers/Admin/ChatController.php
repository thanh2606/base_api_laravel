<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        // Lấy danh sách admins để chat (trừ admin hiện tại)
        $admins = Admin::where('id', '!=', auth('admin')->id())
            ->select('id', 'name', 'email')
            ->take(20)
            ->get();

        return Inertia::render('chat/Index', [
            'admins' => $admins,
            'currentAdmin' => [
                'id' => auth('admin')->id(),
                'name' => auth('admin')->user()->name,
                'email' => auth('admin')->user()->email,
            ],
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'receiver_id' => 'required|integer|exists:admins,id',
        ]);

        $message = $request->message;
        $receiverId = $request->receiver_id;
        $currentAdmin = [
            'id' => auth('admin')->id(),
            'name' => auth('admin')->user()->name,
            'email' => auth('admin')->user()->email,
        ];

        // Broadcast tin nhắn đến admin nhận
        event(new ChatMessageEvent($message, $currentAdmin, $receiverId));

        return response()->json([
            'status' => 'success',
            'message' => 'Tin nhắn đã được gửi đến admin',
        ]);
    }
}
