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
        Schema::create('product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_name');
            $table->text('description');
            $table->double('price');
            $table->integer('stock_quantity');
            $table->integer('category_id')->unsigned();
            $table->integer('manufacturer_id')->unsigned();
            $table->integer('product_view')->default(0);
            $table->string('image')->nullable();
            $table->integer('sold_quantity')->default(0);

            //Khóa ngoại 
            $table->foreign('category_id')->references('category_id')->on('category')->onDelete('cascade');
            $table->foreign('manufacturer_id')->references('manufacturer_id')->on('manufacturer')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->timestamps();


            // Thêm fulltext index sau khi đã định nghĩa các cột
            $table->fullText(['product_name', 'description']);

             // Thêm cột deleted_at để lưu trữ dữ liệu xóa
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['manufacturer_id']);
            $table->dropFullText(['product_name', 'description']);
            $table->dropColumn('slug');
        });
        Schema::dropIfExists('product');
    }
};
