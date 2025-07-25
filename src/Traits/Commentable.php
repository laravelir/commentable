<?php

namespace Laravelir\Commentable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Commentable
{
    protected static function bootCommentable()
    {
        static::deleted(function ($commentable) {
            foreach ($commentable->comments as $comment) {
                $comment->delete();
            }
        });
    }

    public function commentableModel(): string
    {
        return config('commentable.model');
    }

    public function comments(): MorphMany
    {
        return $this->commentsRelation();
    }

    public function commentsRelation(): MorphMany
    {
        return $this->morphMany($this->commentableModel(), 'commentable');
    }

    public function approvedComments()
    {
        return $this->morphMany($this->commentableModel(), 'commentable')->approved();
    }

    public function commentAs(Model $user, string $comment)
    {
        $commentClass = config('commentable.model');

        $comment = $commentClass::create([
            'comment' => $comment,
            // 'approved' => ($user instanceof User) ? !$user->mustBeCommentApprove($this) : false,
            'commentable_id' => $this->id,
            'commentable_type' => get_class($this),
            'commentorable_id'   => $user->id,
            'commentorable_type' => get_class($user),
        ]);

        return $comment;
    }
}
