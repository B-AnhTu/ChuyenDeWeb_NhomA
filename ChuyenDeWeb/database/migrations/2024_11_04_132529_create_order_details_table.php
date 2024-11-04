<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id(); // Đây sẽ là kiểu unsignedBigInteger
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('product_id'); // Thay đổi thành unsignedInteger để phù hợp với product_id
            $table->foreign('product_id')->references('product_id')->on('product'); // Thiết lập khóa ngoại
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
