<?php

namespace Laravelir\Commentable\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Laravelir\Commentable\Models\Comment;

class CommentUpdated
{
    use SerializesModels, Dispatchable, InteractsWithSockets;

    public function __construct(public Comment $comment) {}
}
