<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', // ID of the customer who placed the order
        'sale_manager_id', // ID of the sale manager handling the order
        'amount', // Total order amount
        'status', // Order status (e.g., pending, completed, cancelled)
        'other_charges', // Additional charges
        'total_discount', // Total discount on the order
        'payment_method', // Payment method used for the order
        'order_date', // Date of the order
        'note', // Additional notes
        'updated_by', // User who last updated the order
        'deleted_by', // User who soft deleted the order
        'created_by', // The user who created the order
    ];

    /**
     * Define relationship with the User model (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Define relationship with the customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Define relationship with the sale manager.
     */
    public function saleManager()
    {
        return $this->belongsTo(User::class, 'sale_manager_id');
    }

    /**
     * Define relationship with the User model (editor).
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Soft delete column.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Automatically track who updated or deleted the order.
     */
    protected static function booted()
    {
        static::creating(function ($order) {
            $order->created_by = auth()->id(); // Capture the user ID of the creator
        });

        static::updating(function ($order) {
            $order->updated_by = auth()->id(); // Capture the user ID of the updater
        });

        static::deleting(function ($order) {
            $order->deleted_by = auth()->id(); // Capture the user ID of the deleter
        });
    }
}
