<?php

namespace Laravelir\Commentable\Traits;

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

    public function mustBeCommentApprove(): bool
    {
        return config('commentable.need_approve') ?? true;
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
        return $this->morphMany($this->commentableModel(), 'commentorable')->where('approved', true);
    }

    public function commentFor(Commentable $commentable, string $commentText = '', int $rate = 0): Comment
    {
        $commentModel = $this->commentableModel();

        $comment = new $commentModel([
            'comment'        => $commentText,
            'rate'           => $commentable->canBeRated() ? $rate : null,
            'approved'       => $commentable->mustBeApproved() && !$this->canCommentWithoutApprove() ? false : true,
            'commented_id'   => $this->primaryId(),
            'commented_type' => get_class(),
        ]);

        $commentable->comments()->save($comment);

        return $comment;
    }

    public function comment($data, Model $creator, Model $parent = null)
    {
        $commentableModel = $this->commentableModel();

        $comment = (new $commentableModel())->createComment($this, $data, $creator);

        if (!empty($parent)) {
            $parent->appendNode($comment);
        }

        return $comment;
    }

    public function commentAsUser(?Model $user, string $comment)
    {
        $commentClass = $this->commentableModel();

        $comment = new $commentClass([
            'comment' => $comment,
            'approved' => ($user instanceof User) ? !$user->mustBeCommentApprove($this) : false,
            'commentor_id' => is_null($user) ? null : $user->getKey(),
            'commentable_id' => $this->getKey(),
            'commentable_type' => get_class(),
        ]);

        return $this->comments()->save($comment);
    }

    public function hasComments(Commentable $commentable): bool
    {
        return $this->comments()
            ->where([
                'commentable_id'   => $commentable->primaryId(),
                'commentable_type' => get_class($commentable),
            ])
            ->exists();
    }

}
