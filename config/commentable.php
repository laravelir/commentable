<?php

use Laravelir\Commentable\Policy\CommentPolicy;

// config file for laravelir/commentable
return [

    /**
     * Comment owner model
     * Must extend Illuminate\Contracts\Auth\Authenticatable
     * Must implement LakM\Comments\Contracts\CommenterContract
     */
    'user_model' => User::class,

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
        'create-reply' => [ReplyPolicy::class, 'create'],
        'update-reply' => [ReplyPolicy::class, 'update'],
        'delete-reply' => [ReplyPolicy::class, 'delete'],
    ],

    /**
     * The Comment Controller.
     * Change this to your own implementation of the CommentController.
     * You can use the \Laravelista\Comments\CommentControllerInterface
     * or extend the \Laravelista\Comments\CommentController.
     */
    'controller' => '\Laravelista\Comments\WebCommentController',


    /**
     * By default comments posted are marked as approved. If you want
     * to change this, set this option to true. Then, all comments
     * will need to be approved by setting the `approved` column to
     * `true` for each comment.
     *
     * To see only approved comments use this code in your view:
     *
     *     @comments([
     *         'model' => $book,
     *         'approved' => true
     *     ])
     *
     */
    'approval_required' => false,

];
