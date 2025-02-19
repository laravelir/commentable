<?php

namespace Laravelir\Commentable\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Commentator
{
    public function comments(): MorphMany;

    public function needsCommentApproval($model): bool;
}
