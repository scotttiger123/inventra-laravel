<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->dateTime('order_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('total_amount', 10, 2);
            $table->enum('discount_type', ['flat', 'percentage'])->default('flat');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('other_charges', 10, 2)->default(0);
            $table->decimal('net_total', 10, 2)->virtualAs(DB::raw("
                CASE
                    WHEN discount_type = 'percentage' THEN (total_amount - (total_amount * discount_amount / 100)) + other_charges
                    ELSE (total_amount - discount_amount) + other_charges
                END
            "));
            $table->decimal('paid', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'canceled'])->default('pending');
            $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer', 'paypal', 'other'])->default('cash');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'returned'])->default('pending');
            $table->unsignedBigInteger('sale_manager_id')->nullable();  


            $table->timestamps();
            $table->softDeletes();
            

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('sale_manager_id')->references('id')->on('users')->onDelete('set null');
            


        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
