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
            Schema::create('payment_heads', function (Blueprint $table) {
                $table->id(); // Primary Key
                $table->string('name'); // Name of the payment head (e.g., Customer, Supplier)
                $table->softDeletes(); // Soft delete column (deleted_at)
                
                // Foreign keys to track who created/updated/deleted the record
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
    
                $table->timestamps(); // Created at and updated at timestamps
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_heads');
    }
};
