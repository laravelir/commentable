<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Laravelir\Commentable\Models\Comment;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commentable_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->default(0);
            $table->morphs('commentorable'); // user
            $table->morphs('commentable'); // model
            $table->text('comment');
            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(["commentable_type", "commentable_id"]);
        });

        // Schema::create('commentable_reactions', function (Blueprint $table) {
        //     $table->id();
        //     $table->morphs('commentorable'); // user
        //     $table->foreignIdFor(Comment::class, 'comment_id');
        //     $table->char('type', 1);
        //     $table->timestamps();
        // });
    }

    public function down()
    {
        Schema::dropIfExists('commentable_comments');
        // Schema::dropIfExists('commentable_reactions');
    }
};
