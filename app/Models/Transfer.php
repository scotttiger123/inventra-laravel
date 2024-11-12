<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'from_warehouse_id',
        'to_warehouse_id',
        'quantity',
        'date',
        'product_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    /**
     * Get the warehouse from which items are transferred.
     */
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    /**
     * Get the warehouse to which items are transferred.
     */
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    /**
     * Get the user who created the transfer.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the transfer.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the transfer.
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Event hooks to automatically set created_by, updated_by, and deleted_by

    protected static function boot()
    {
        parent::boot();

        // Automatically set created_by when creating a new transfer
        static::creating(function ($transfer) {
            $transfer->created_by = auth()->id();
        });

        // Automatically set updated_by when updating a transfer
        static::updating(function ($transfer) {
            $transfer->updated_by = auth()->id();
        });

        // Automatically set deleted_by when soft-deleting a transfer
        static::deleting(function ($transfer) {
            $transfer->deleted_by = auth()->id();
            $transfer->save(); // Save the deleted_by change before the soft delete
        });
    }
}
