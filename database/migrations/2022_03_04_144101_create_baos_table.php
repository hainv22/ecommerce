<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baos', function (Blueprint $table) {
            $table->id();
            $table->string('b_name');
            $table->integer('b_weight')->comment('số cân nặng');
            $table->integer('b_fee')->default(0)->nullable()->comment('số tiền / 1kg khi giao xong');
            $table->tinyInteger('b_status');
            $table->string('b_note')->nullable();
            $table->unsignedBigInteger('b_transaction_id');
            $table->timestamp('b_success_date')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baos');
    }
}
