<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeMoneyOwnerHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_money_owner_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cmh_owner_china_id');
            $table->integer('cmh_money')->comment('+/- so tien')->default(0);
            $table->integer('cmh_money_after')->comment('so tien sau khi +/-')->default(0);
            $table->integer('cmh_yuan')->default(0)->comment('giá tiền trung khi thanh toán tại thời điểm trả');
            $table->text('cmh_content')->nullable();
            $table->integer('cmh_role')->default(2)->comment('1 là của admin(mình) 2 là của chung');
            $table->unsignedBigInteger('cmh_owner_transaction_id')->default(9999999999);
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
        Schema::dropIfExists('change_money_owner_histories');
    }
}
