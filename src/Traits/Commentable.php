<?php

namespace Laravelir\Commentable\Traits;

use App\Models\User;
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

    public function mustBeCommentApprove(): bool
    {
        return config('commentable.need_approve') ?? true;
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
        return $this->morphMany($this->commentableModel(), 'commentable')->where('approved_at', '!=', null);
    }

    public function comment(string $comment, $guard = 'web')
    {
        return $this->commentAsUser(auth($guard)->user(), $comment);
    }

    public function commentAsUser(?Model $user, string $comment)
    {
        $commentClass = config('commentable.model');

        $comment = new $commentClass([
            'comment' => $comment,
            'approved' => ($user instanceof User) ? !$user->mustBeCommentApprove($this) : false,
            'commentor_id' => is_null($user) ? null : $user->getKey(),
            'commentable_id' => $this->getKey(),
            'commentable_type' => get_class(),
        ]);

        return $this->comments()->save($comment);
    }

    public function updateComment($id, $data, Model $parent = null)
    {
        $commentableModel = $this->commentableModel();

        $comment = (new $commentableModel())->updateComment($id, $data);

        if (!empty($parent)) {
            $parent->appendNode($comment);
        }

        return $comment;
    }

    public function deleteComment($id): bool
    {
        $commentableModel = $this->commentableModel();

        return (bool) (new $commentableModel())->forceDelete($id);
    }


    public function commentCount(): int
    {
        return $this->comments()->count();
        if (!$this->mustBeApproved()) {
            return $this->comments()->count();
        }

        return $this->comments()->approvedComments()->count();
    }
}
