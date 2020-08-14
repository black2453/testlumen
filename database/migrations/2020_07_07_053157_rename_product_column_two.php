<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductColumnTwo extends Migration
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
            $table->renameColumn('yuanjia','original_price');
            $table->renameColumn('quan_jine','coupon_value');
            $table->renameColumn('jiage','price');
            $table->renameColumn('recommend_reason','reason');
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
            $table->renameColumn('original_price','yuanjia');
            $table->renameColumn('coupon_value','quan_jine');
            $table->renameColumn('price','jiage');
            $table->renameColumn('reason','recommend_reason');
        });
    }
}
