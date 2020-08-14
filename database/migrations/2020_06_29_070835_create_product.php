<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('origin_id',8)->comment('原資料id');
            $table->string('pic', 999)->comment('商品圖片');
            $table->string('dtitle')->comment('商品簡短標題');
            $table->decimal('yuanjia',10,2)->comment('商品原價');
            $table->decimal('xiaoliang',10,2)->comment('累计销量');
            $table->decimal('jiage',10,2)->comment('售價');
            $table->decimal('quanJine',10,2)->comment('独家优惠(折價)');
            $table->timestamp('createTime')->comment('上新时间');
            $table->string('goodsId')->comment('未知');
            $table->unsignedTinyInteger('huodongType')->default(0)->comment('未知');
            $table->unsignedTinyInteger('hzQuanOver')->default(0)->comment('未知');
            $table->string('quanId')->comment('未知');
            $table->string('salesNum')->comment('未知');
            $table->timestamp('startTime')->nullable();
            $table->string('title', 999)->comment('商品標題');
            $table->unsignedTinyInteger('tmall')->default(0)->comment('未知');
            $table->unsignedTinyInteger('video')->default(0)->comment('未知');
            $table->decimal('yongjin',10,2)->comment('未知');
            $table->string('fashionTag')->comment('未知');
            $table->string('marketId')->nullable()->comment('未知');
            $table->string('basePriceText')->comment('未知');
            $table->string('basePrice')->comment('未知');
            $table->string('baseSaleNumText')->comment('未知');
            $table->unsignedTinyInteger('red_packet')->default(0)->comment('未知');
            $table->decimal('thirtySellNun',10,2)->comment('未知');
            $table->string('promotion')->comment('未知');
            $table->string('labelOne')->nullable()->comment('未知');
            $table->string('labelTwo')->nullable()->comment('未知');
            $table->string('comments')->comment('未知');
            $table->unsignedTinyInteger('beforePriceLabelType')->default(0)->comment('未知');
            $table->decimal('renqi',10,7)->comment('未知');
            $table->string('category_id',20)->comment('未知');
            $table->unsignedTinyInteger('lowest')->default(0)->comment('未知');
            $table->string('quanMLink')->comment('未知');
            $table->decimal('comment',10,2)->default(0)->comment('未知');
            $table->decimal('quan_num',10,2)->comment('未知');
            $table->timestamp('quan_time')->nullable()->comment('未知');
            $table->string('is_delete')->comment('未知');
            $table->string('is_online')->comment('未知');
            $table->string('market_group')->nullable()->comment('未知');

            $table->unsignedTinyInteger('beforeTitleLables')->default(0)->comment('商品標前面的店家icon');

            $table->string('underPriceLabels')->nullable()->default('')->comment('電商用語');

            $table->timestamps();
            $table->softDeletes();

            $table->index('origin_id', 'origin_id');
            $table->index('huodongType', 'huodongType');
            $table->index('hzQuanOver', 'hzQuanOver');
            $table->index('tmall', 'tmall');
            $table->index('video', 'video');
            $table->index('beforePriceLabelType', 'beforePriceLabelType');
            $table->index('lowest', 'lowest');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
