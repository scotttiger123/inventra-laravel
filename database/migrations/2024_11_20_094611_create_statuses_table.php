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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('status_name'); // Name of the status (e.g., "Return Pending")
            $table->text('description')->nullable(); // Description of the status
            $table->timestamps(); // created_at and updated_at timestamps
            $table->softDeletes(); // Soft delete column (deleted_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
