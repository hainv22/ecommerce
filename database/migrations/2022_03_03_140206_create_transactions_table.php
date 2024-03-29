<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tst_user_id')->default(0)->index();
            $table->unsignedBigInteger('tst_total_money')->default(0);
            $table->integer('tst_total_products')->default(0);
            $table->string('tst_note')->nullable();
            $table->tinyInteger('tst_status')->default(1);
            $table->tinyInteger('tst_type')->default(1)->comment('1 thanh toan thuong, 2 la online');
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
        Schema::dropIfExists('transactions');
    }
}
