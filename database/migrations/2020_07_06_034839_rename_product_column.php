<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product', function (Blueprint $table){
            $table->renameColumn('underPriceLabels','under_price_labels');
            $table->renameColumn('beforeTitleLables','before_title_lables');
            $table->renameColumn('quanMLink','quan_link');
            $table->renameColumn('beforePriceLabelType','before_price_label_type');
            $table->renameColumn('labelTwo','label_two');
            $table->renameColumn('labelOne','label_one');
            $table->renameColumn('thirtySellNun','thirty_sell_nun');
            $table->renameColumn('baseSaleNumText','base_sale_num_text');
            $table->renameColumn('basePrice','base_price');
            $table->renameColumn('basePriceText','base_price_text');
            $table->renameColumn('marketId','market_id');
            $table->renameColumn('fashionTag','fashion_tag');
            $table->renameColumn('startTime','start_time');
            $table->renameColumn('salesNum','sales_num');
            $table->renameColumn('quanId','quan_id');
            $table->renameColumn('hzQuanOver','hz_quan_over');
            $table->renameColumn('huodongType','huodong_type');
            $table->renameColumn('goodsId','goods_id');
            $table->renameColumn('createTime','create_time');
            $table->renameColumn('quanJine','quan_jine');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('product', function (Blueprint $table){
            $table->renameColumn('under_price_labels','underPriceLabels');
            $table->renameColumn('before_title_lables','beforeTitleLables');
            $table->renameColumn('quan_link','quanMLink');
            $table->renameColumn('before_price_label_type','beforePriceLabelType');
            $table->renameColumn('label_two','labelTwo');
            $table->renameColumn('label_one','labelOne');
            $table->renameColumn('thirty_sell_nun','thirtySellNun');
            $table->renameColumn('base_sale_num_text','baseSaleNumText');
            $table->renameColumn('base_price','basePrice');
            $table->renameColumn('base_price_text','basePriceText');
            $table->renameColumn('market_id','marketId');
            $table->renameColumn('fashion_tag','fashionTag');
            $table->renameColumn('start_time','startTime');
            $table->renameColumn('sales_num','salesNum');
            $table->renameColumn('quan_id','quanId');
            $table->renameColumn('hz_quan_over','hzQuanOver');
            $table->renameColumn('huodong_type','huodongType');
            $table->renameColumn('goods_id','goodsId');
            $table->renameColumn('create_time','createTime');
            $table->renameColumn('quan_jine','quanJine');

        });
    }
}
