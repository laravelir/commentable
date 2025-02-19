<?php

namespace Laravelir\Commentable\Events;

use Illuminate\Queue\SerializesModels;
use Laravelir\Commentable\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CommentDeleted
{
    use SerializesModels, InteractsWithSockets, Dispatchable;

    public function __construct(public Comment $comment) {}
}
