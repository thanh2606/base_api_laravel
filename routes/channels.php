<?php

use Illuminate\Support\Facades\Broadcast;

// Channel riêng cho từng user - notifications
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel cho chat messages - mỗi user có kênh riêng để nhận tin nhắn
Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // Cho phép user lắng nghe kênh chat của chính họ
    return (int) $user->id === (int) $userId;
});

// Channel cho admin chat - mỗi admin có kênh riêng để nhận tin nhắn
Broadcast::channel('chat.{adminId}', function ($admin, $adminId) {
    // Kiểm tra nếu là admin guard và admin đó được phép lắng nghe
    if (auth('admin')->check()) {
        return (int) auth('admin')->id() === (int) $adminId;
    }

    return false;
});
