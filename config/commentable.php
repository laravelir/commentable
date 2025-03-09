<?php

use Laravelir\Commentable\Policy\CommentPolicy;

// config file for laravelir/commentable
return [

    /**
     * Comment owner model
     * Must extend Illuminate\Contracts\Auth\Authenticatable
     * or
     * config('auth.providers.users.model')
     */
    'user_model' => config('auth.providers.users.model'),

    /**
     * To extend the base Comment model one just needs to create a new
     * CustomComment model extending the Comment model shipped with the
     * package and change this configuration option to their extended model.
     */
    'model' => Laravelir\Commentable\Models\Comment::class,

    /**
     * You can customize the behaviour of these permissions by
     * creating your own and pointing to it here.
     */
    'permissions' => [
        'create-comment' => [CommentPolicy::class, 'create'],
        'update-comment' => [CommentPolicy::class, 'update'],
        'delete-comment' => [CommentPolicy::class, 'delete'],
        // 'create-reply' => [ReplyPolicy::class, 'create'],
        // 'update-reply' => [ReplyPolicy::class, 'update'],
        // 'delete-reply' => [ReplyPolicy::class, 'delete'],
    ],
];
