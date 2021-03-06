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
            $table->bigIncrements('id');
            $table->string("name")->unique();
            $table->string("slug");
            $table->text("description")->nullable();
            $table->integer("quantity")->unsigned();
            $table->integer("weight")->unsigned();
            $table->integer("price")->unsigned();
            $table->string("status")->nullable();
            $table->timestamps();
            //後でreviewとlike関連のを付け足す
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
