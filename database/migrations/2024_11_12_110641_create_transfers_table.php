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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing transfer ID
            $table->unsignedBigInteger('from_warehouse_id'); // Foreign key to the originating warehouse
            $table->unsignedBigInteger('to_warehouse_id'); // Foreign key to the destination warehouse
            $table->decimal('quantity', 8, 2); // Quantity of items transferred
            $table->date('date')->nullable(); // Date of transfer (nullable)
            $table->timestamps(); // Created at and updated at timestamps
    
            // Soft delete columns to track who deleted the record
            $table->unsignedBigInteger('created_by')->nullable(); // User who created the transfer
            $table->unsignedBigInteger('updated_by')->nullable(); // User who last updated the transfer
            $table->unsignedBigInteger('deleted_by')->nullable(); // User who deleted the transfer (nullable)
    
            // Foreign key constraints
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
