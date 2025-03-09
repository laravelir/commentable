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

    protected $with = ['children', 'commentorable', 'commentable'];

    // protected $dispatchesEvents = [
    //     'created' => CommentCreated::class,
    //     'updated' => CommentUpdated::class,
    //     'deleted' => CommentDeleted::class,
    // ];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
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

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    public function getChildren($columns = ['*'])
    {
        return $this->children()->get($columns);
    }

    public function children()
    {
        return $this->hasMany(config('commentable.model'), 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(config('commentable.model'), 'id', 'parent_id');
    }

    // public function reactions(): MorphMany
    // {
    //     return $this->morphMany(Reaction::class, 'commentorable');
    // }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved_at', '!=', null);
    }

    public function setCommentAttribute($value)
    {
        $this->attributes['comment'] = str_replace(PHP_EOL, "<br>", $value);
    }

    public function approve()
    {
        $this->update([
            'approved_at' => now(),
        ]);

        return $this;
    }

    public function disapprove()
    {
        $this->update([
            'approved_at' => null,
        ]);

        return $this;
    }
}
