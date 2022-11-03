<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCmhMoneyBeforeToChangeMoneyOwnerHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('change_money_owner_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('cmh_money_before')->default(0)->comment('so tien truoc khi +/-');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('change_money_owner_histories', function (Blueprint $table) {
            $table->dropColumn(['cmh_money_before']);
        });
    }
}
