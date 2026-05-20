<?php

namespace App\Enum;

enum NotificationType: string
{
    case Post = 'post';
    case Follower = 'follower';
    case Like = 'like';
    case Replies = 'replies';
}
