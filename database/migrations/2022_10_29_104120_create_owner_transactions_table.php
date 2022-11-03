<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ot_owner_china_id')->index();
            $table->unsignedBigInteger('ot_total_money')->default(0)->comment('tien trung');
            $table->integer('ot_total_products')->default(0);
            $table->string('ot_note')->nullable();
            $table->tinyInteger('ot_status')->default(1);
            $table->date('ot_order_date')->default(now())->comment('Ngay boc hang');
            $table->integer('ot_transaction_role')->default(2)->comment('1 là của admin(mình) 2 là của chung');
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
        Schema::dropIfExists('owner_transactions');
    }
}
