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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('orderId');
            $table->json('cart_id');
            $table->foreignId('user_id')->constrained('users');
            $table->json('product_id');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->string('delivery_address');
            $table->decimal('delivery_latitude', 11,7)->nullable();
            $table->decimal('delivery_longitude', 11,7)->nullable();
            $table->enum('delivery_type', ['delivery', 'pickup'])->default('delivery');
            $table->enum('payment_from', ['wallet', 'card']);
            $table->foreignId('rider_id')->nullable()->constrained('riders');
            $table->string('delivery_instruction')->nullable();
            $table->boolean('vendor_accepted_order')->default(false);
            $table->boolean('rider_accepted_order')->default(false);
            $table->boolean('dispatcher_to_vendor')->default(false);
            $table->boolean('rider_received_order')->default(false);
            $table->boolean('order_arrived')->default(false);
            $table->boolean('user_received_order')->default(false);
            $table->enum('order_status', ['pending', 'ongoing', 'completed']);
            $table->enum('payment_status', ['paid', 'not paid']);
            $table->integer("delivery_fee");
            $table->integer("total_amount");
            $table->softDeletes();
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
};
