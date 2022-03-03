<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('pro_name')->unique();
            $table->unsignedBigInteger('pro_category_id');
            $table->unsignedBigInteger('pro_user_id')->default(0);
            $table->integer('pro_price')->default(0)->comment('giá');
            $table->tinyInteger('pro_sale')->default(0)->comment('giảm giá');
            $table->string('pro_avatar')->nullable();
            $table->tinyInteger('pro_hot')->default(0);
            $table->tinyInteger('pro_active')->default(1);
            $table->integer('pro_pay')->default(0)->comment('số lượt mua');
            $table->mediumText('pro_description')->nullable();
            $table->text('pro_content')->nullable();
            $table->integer('pro_number')->default(0)->comment('tổng số lượng');
            $table->tinyInteger('pro_country')->default(0)->comment('xuất xứ');
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
        Schema::dropIfExists('products');
    }
}
