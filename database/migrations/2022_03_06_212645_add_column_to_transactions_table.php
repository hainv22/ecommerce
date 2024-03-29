<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->date('tst_order_date')->default(now())->comment('ngày đặt hàng');;
            $table->date('tst_expected_date')->default(now())->comment('ngày dự kiến giao hàng thành công');
            $table->integer('tst_deposit')->default(0)->comment('số tiền khách đặt cọc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['tst_order_date', 'tst_expected_date', 'tst_deposit']);
        });
    }
}
