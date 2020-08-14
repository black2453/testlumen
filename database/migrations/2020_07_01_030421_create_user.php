<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account',20)->comment('用戶帳號');
            $table->string('password')->comment('用戶密碼');
            $table->unsignedTinyInteger('is_auth')->default(0)->comment('是否以驗證信箱');
            $table->string('email')->nullable()->comment('電子信箱');
            $table->string('token')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
