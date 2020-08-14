<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_image', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->comment('商品id');
            $table->string('img', 999)->comment('商品圖片網址');
            $table->decimal('width',10,2)->comment('商品圖片寬');
            $table->decimal('height',10,2)->comment('商品圖片高');

            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id', 'product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_image');
    }
}
