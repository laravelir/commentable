<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Laravelir\Commentable\Models\Comment;
use Illuminate\Database\Migrations\Migration;

class CreateTablesTable extends Migration
{

    public function up()
    {
        Schema::create('commentable_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->default(0);
            $table->morphs('commentorable');
            $table->morphs('commentable');
            $table->text('comment');
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(["commentable_type", "commentable_id"]);

            $table->foreign('parent_id')
                ->references('id')
                ->on('commentables')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('commentable_reactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('commentorable');
            $table->foreignIdFor(Comment::class);
            $table->char('type', 1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentable_comments');
        Schema::dropIfExists('commentable_reactions');
    }
}
