<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUseMoneyHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('use_money_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('umh_money')->comment('số tiền sử dụng/ số tiền trả trung của')->default(0);
            $table->integer('umh_money_after')->comment('số tiền sau khi sử dung')->default(0);
            $table->text('umh_content')->nullable();
            $table->date('umh_use_date')->comment('ngay rut su dung tien');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('use_money_histories');
    }
}
