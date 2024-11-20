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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('purchase_id');
            $table->string('custom_purchase_id')->unique(); // Custom unique identifier
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade'); // Branch relationship
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade'); // Supplier relationship
            $table->foreignId('purchase_manager_id')->nullable()->constrained('users')->onDelete('set null'); // Manager relationship
            $table->string('status')->default('pending'); // Purchase status
            $table->text('staff_note')->nullable(); // Notes by staff
            $table->text('purchase_note')->nullable(); // Additional purchase notes
            $table->decimal('other_charges', 15, 2)->default(0); // Additional charges
            $table->string('discount_type')->nullable(); // Discount type (percentage/flat)
            $table->decimal('discount_amount', 15, 2)->default(0); // Discount value
            $table->decimal('paid', 15, 2)->default(0); // Paid amount
            $table->date('purchase_date'); // Purchase date
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // Creator
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // Updater
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null'); // Deleter
            $table->softDeletes(); // Soft delete
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
