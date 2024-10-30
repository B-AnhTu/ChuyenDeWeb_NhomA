<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_comment', function (Blueprint $table) {
            $table->increments('comment_id');
            $table->unsignedInteger('blog_id');
            $table->unsignedInteger('user_id');
            $table->text('content');
            $table->timestamps();
            $table->integer('status')->default(0);
            $table->string('email')->nullable();

            $table->foreign('blog_id')->references('blog_id')->on('blog');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comment');
    }
};
