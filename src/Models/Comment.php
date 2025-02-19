<?php

namespace Laravelir\Commentable\Models;

use Exception;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravelir\Commentable\Events\CommentCreated;
use Laravelir\Commentable\Events\CommentDeleted;
use Laravelir\Commentable\Events\CommentUpdated;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'commentable_comments';

    protected $guarded = [];

    protected $with = ['children', 'commentator', 'commentable'];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'date'
    ];

    protected $dispatchesEvents = [
        'created' => CommentCreated::class,
        'updated' => CommentUpdated::class,
        'deleted' => CommentDeleted::class,
    ];

    public static function boot(): void
    {
        parent::boot();


        self::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });

        static::deleting(function (self $model) {
            if (config('comments.delete_replies_along_comments')) {
                $model->comments()->delete();
            }
        });
    }


    public function commentorable(): MorphTo
    {
        return $this->morphTo();
    }


    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function getChildren($columns = ['*'])
    {
        return $this->children()->get($columns);
    }

    public function children()
    {
        return $this->hasMany(Comment::get('social.comments.model'), 'parent_id', 'id');
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'owner');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::get('social.comments.model'), 'id', 'parent_id');
    }

    public function scopeApproved(Builder $query, $approved): Builder
    {
        return $query->where('approved', $approved);
    }

    public function setCommentAttribute($value)
    {
        $this->attributes['comment'] = str_replace(PHP_EOL, "<br>", $value);
    }

    public function approve()
    {
        $this->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return $this;
    }

    public function disapprove()
    {
        $this->update([
            'is_approved' => false,
            'approved_at' => null,
        ]);

        return $this;
    }


    // public function commentator()
    // {
    //     return $this->belongsTo($this->getAuthModelName(), 'user_id');
    // }
    //
    protected function getAuthModelName()
    {
        if (config('comments.user_model')) {
            return config('comments.user_model');
        }

        if (!is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('Could not determine the commentator model name.');
    }
}
