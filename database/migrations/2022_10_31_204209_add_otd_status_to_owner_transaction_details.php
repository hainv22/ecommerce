<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtdStatusToOwnerTransactionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('owner_transaction_details', function (Blueprint $table) {
            $table->tinyInteger('otd_status')->default(1)->comment('1 chưa về, 2 đã về đến kho');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('owner_transaction_details', function (Blueprint $table) {
            $table->dropColumn(['otd_status']);
        });
    }
}
