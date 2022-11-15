<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerBaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_baos', function (Blueprint $table) {
            $table->id();
            $table->integer('b_weight')->comment('số cân nặng');
            $table->string('b_note')->nullable();
            $table->unsignedBigInteger('b_owner_transaction_id');
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
        Schema::dropIfExists('owner_baos');
    }
}
