<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{

    public function up()
    {
        Schema::create('commentables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->default(0);
            $table->morphs('commentorable');
            $table->morphs('commentable');
            $table->text('comment');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(["commentable_type", "commentable_id"]);

            $table->foreign('parent_id')
                ->references('id')
                ->on('commentables')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentables');
    }
}
