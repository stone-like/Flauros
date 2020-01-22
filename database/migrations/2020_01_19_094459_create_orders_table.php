<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("address_id")->unsigned();
            $table->bigInteger("user_id")->unsigned();
            $table->bigInteger("order_status_id")->unsigned();
            $table->integer("total")->unsigned();//税込みでの価格
            $table->integer("discount")->unsigned();//割引
            $table->integer("shipping_fee")->unsigned();//送料
            $table->integer("payment")->unsigned();//最終的な支払い
            

            $table->string("tracking_number");
            $table->foreign("address_id")->references("id")->on("addresses")->onDelete("cascade");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
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
        Schema::dropIfExists('orders');
    }
}
