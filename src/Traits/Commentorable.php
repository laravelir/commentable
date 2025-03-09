<?php

namespace Laravelir\Commentable\Traits;

use Illuminate\Database\Eloquent\Model;
use Laravelir\Commentable\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Commentorable
{
    public static function booted()
    {
        static::deleting(function ($model) {
            $model->comments()->delete();
        });
    }

    public function commentableModel()
    {
        return config('commentable.model');
    }

    public function comments()
    {
        return $this->commentsRelation();
    }

    public function commentsRelation(): HasMany
    {
        return $this->morphMany($this->commentableModel(), 'commentorable');
    }

    public function approvedComments()
    {
        return $this->morphMany($this->commentableModel(), 'commentorable')->approved();
    }

    public function commentFor(Model $commentableModel, string $commentText): Comment
    {
        $commentModel = $this->commentableModel();

        $comment = new $commentModel([
            'comment'        => $commentText,
            // 'approved'       => $commentable->mustBeApproved() && !$this->canCommentWithoutApprove() ? false : true,
            'commentorable_id'   => $this->primaryId(),
            'commentorable_type' => get_class($this),
            'commentable_id'   => $commentableModel->id,
            'commentable_type' => get_class($commentableModel),
        ]);

        return $comment;
    }
}
