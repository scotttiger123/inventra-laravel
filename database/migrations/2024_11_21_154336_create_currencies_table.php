<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name')->nullable(false); // Currency name
            $table->string('code', 10)->unique()->nullable(false); // Currency code (e.g., USD, EUR)
            $table->string('symbol', 10)->nullable(); // Currency symbol (e.g., $, €)
            $table->decimal('exchange_rate', 15, 6)->nullable(false); // Exchange rate
            $table->unsignedBigInteger('created_by')->nullable(); // Tracks user who created this
            $table->softDeletes(); // Adds deleted_at column for soft deletes
            $table->timestamps(); // Adds created_at and updated_at columns

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null'); // Foreign key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
