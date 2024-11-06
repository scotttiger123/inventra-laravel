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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();  // auto-incrementing primary key
            $table->string('name');  // Supplier's name
            $table->string('email')->unique();  // Email (unique)
            $table->string('phone')->nullable();  // Supplier's phone number (nullable)
            $table->text('address')->nullable();  // Supplier's address (nullable)
            $table->string('city')->nullable();  // Supplier's city (nullable)
            $table->string('po_box')->nullable();  // PO Box (nullable)
            $table->decimal('initial_balance', 10, 2)->nullable();  // Initial balance (nullable)
            $table->string('tax_number')->nullable();  // Tax number (nullable)

            // Discount type field: either 'flat' or 'percentage'
            $table->enum('discount_type', ['flat', 'percentage'])->nullable();  // 'flat' or 'percentage'
            
            // A single discount value field (flat or percentage value)
            $table->decimal('discount_value', 10, 2)->nullable();  // Discount value (nullable)

            $table->timestamps();  // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
