<?php

namespace Laravelir\Commentable\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Reaction extends Model
{
    use SoftDeletes;

    protected $table = 'commentable_reactions';

    protected $guarded = [];

    public function comment()
    {
        $this->belongsTo(Comment::class);
    }
}
