<?php

namespace Laravelir\Commentable\Events;

use Illuminate\Queue\SerializesModels;
use Laravelir\Commentable\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CommentCreated
{
    use SerializesModels, Dispatchable, InteractsWithSockets;

    public function __construct(public Comment $comment) {}
}
