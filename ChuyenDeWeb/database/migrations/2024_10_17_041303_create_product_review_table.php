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
        Schema::create('product_review', function (Blueprint $table) {
            $table->increments('review_id');
            $table->integer('user_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->text('comment');
            $table->timestamps();
            $table->tinyInteger('status')->default(0);

            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_review');
    }
};
