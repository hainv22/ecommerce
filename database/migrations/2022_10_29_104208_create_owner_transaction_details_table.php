<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('otd_owner_transaction_id');
            $table->unsignedBigInteger('otd_product_id');
            $table->integer('otd_qty')->default(0);
            $table->integer('otd_price')->default(0);
            $table->text('otd_note')->nullable();
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
        Schema::dropIfExists('owner_transaction_details');
    }
}
