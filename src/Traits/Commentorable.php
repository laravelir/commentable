<?php

namespace Laravelir\Commentable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function comments(): MorphMany
    {
        return $this->commentsRelation();
    }

    public function commentsRelation()
    {
        return $this->morphMany($this->commentableModel(), 'commentorable');
    }

    public function approvedComments()
    {
        return $this->morphMany($this->commentableModel(), 'commentorable')->approved();
    }

    public function commentFor(Model $commentableModel, string $commentText)
    {
        $commentModel = $this->commentableModel();

        $comment = $commentModel::create([
            'comment'        => $commentText,
            // 'approved'       => $commentable->mustBeApproved() && !$this->canCommentWithoutApprove() ? false : true,
            'commentorable_id'   => $this->id,
            'commentorable_type' => get_class($this),
            'commentable_id'   => $commentableModel->id,
            'commentable_type' => get_class($commentableModel),
        ]);

        return $comment;
    }
}
