<?php

namespace Laravelir\Commentable\Events;

use Illuminate\Queue\SerializesModels;
use Laravelir\Commentable\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class CommentDeleted implements ShouldHandleEventsAfterCommit
{
    use SerializesModels, InteractsWithSockets, Dispatchable;

    public function __construct(public Comment $comment) {}
}
