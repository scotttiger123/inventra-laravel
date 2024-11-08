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
        
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // The user entering the payment
    
            // Polymorphic relationship for customer or supplier
            $table->morphs('payable'); // This creates `payable_id` and `payable_type` (e.g., Customer or Supplier)
    
            $table->decimal('amount', 10, 2); // The amount of the payment
            $table->enum('status', ['pending', 'completed', 'cancelled']); // Payment status
            $table->enum('payment_type', ['credit', 'debit']); // Type of payment (credit or debit)
            
            $table->foreignId('invoice_id')->nullable(); // Foreign key to reference a sale/purchase invoice (if any)
            $table->enum('payment_head', ['customer', 'supplier'])->nullable(); // Payment head (e.g., for customer or supplier)
            
            $table->timestamps(); // Automatically handled created_at and updated_at
            
            // Tracking fields
            $table->foreignId('updated_by')->nullable()->constrained('users'); // User who updated the record
            $table->softDeletes(); // For soft delete (adds deleted_at)
            $table->foreignId('deleted_by')->nullable()->constrained('users'); // User who deleted the record
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
