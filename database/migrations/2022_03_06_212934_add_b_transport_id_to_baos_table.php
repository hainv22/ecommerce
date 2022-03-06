<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBTransportIdToBaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baos', function (Blueprint $table) {
            $table->unsignedBigInteger('b_transport_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baos', function (Blueprint $table) {
            $table->dropColumn(['b_transport_id']);
        });
    }
}
