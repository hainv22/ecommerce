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
            $table->unsignedBigInteger('umh_money')->comment('số tiền +/-')->default(0);
            $table->unsignedBigInteger('umh_money_after')->comment('số tiền sau khi +/-')->default(0);
            $table->unsignedBigInteger('umh_money_before')->default(0)->comment('so tien truoc khi +/-');
            $table->text('umh_content')->nullable();
            $table->tinyInteger('umh_status')->comment('1 la tieu tien bt, 2 do change money owner')->default(1);
            $table->unsignedBigInteger('umh_change_money_owner_id')->default(9999999999);
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
