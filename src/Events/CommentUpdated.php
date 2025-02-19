<?php

namespace Laravelir\Commentable\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Laravelir\Commentable\Models\Comment;

class CommentUpdated implements ShouldDispatchAfterCommit
{
    use SerializesModels, Dispatchable, InteractsWithSockets;

    public function __construct(public Comment $comment) {}
}
