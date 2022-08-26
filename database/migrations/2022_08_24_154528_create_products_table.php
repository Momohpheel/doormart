<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string("name");
            $table->text("description");
            $table->string("image_1");
            $table->string("image_2");
            $table->string("image_3");
            $table->string("image_4");
            $table->string("price");
            $table->integer("like")->default(0);
            $table->foreignId("product_category_id")->constrained('product_categories');
            $table->foreignId("vendor_id")->constrained('vendors');
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
};
