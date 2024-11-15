<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'custom_order_id',
        'branch_id',
        'customer_id', // ID of the customer who placed the order
        'sale_manager_id', // ID of the sale manager handling the order
        'status', 
        'staff_note',
        'sale_note',
        'other_charges', 
        'discount_type', 
        'discount_amount', 
        'paid',
        'order_date', 
        'updated_by', 
        'deleted_by', 
        'created_by', 
    ];


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'custom_order_id');  
    }
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
