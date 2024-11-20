<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'purchase_id'; // Primary key for the purchase table

    protected $fillable = [
        'custom_purchase_id', // Custom purchase identifier
        'branch_id', // Branch where the purchase was made
        'supplier_id', // ID of the supplier
        'purchase_manager_id', // ID of the purchase manager handling the entry
        'status', // Status of the purchase (e.g., completed, pending)
        'staff_note', // Notes added by staff
        'purchase_note', // Notes specific to the purchase
        'additional_charges', // Additional charges applied
        'discount_type', // Discount type (percentage or flat)
        'discount_amount', // Discount value
        'paid', 
        'purchase_date', // Date of the purchase
        'updated_by', // User who updated the record
        'deleted_by', // User who deleted the record
        'created_by', // User who created the record
    ];

    /**
     * Relationship with purchase items.
     */
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'custom_purchase_id');
    }

    /**
     * Define relationship with the User model (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Define relationship with the supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Define relationship with the purchase manager.
     */
    public function purchaseManager()
    {
        return $this->belongsTo(User::class, 'purchase_manager_id');
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
     * Automatically track who created, updated, or deleted the purchase.
     */
    protected static function booted()
    {
        static::creating(function ($purchase) {
            $purchase->created_by = auth()->id(); // Capture the user ID of the creator
        });

        static::updating(function ($purchase) {
            $purchase->updated_by = auth()->id(); // Capture the user ID of the updater
        });

        static::deleting(function ($purchase) {
            $purchase->deleted_by = auth()->id(); // Capture the user ID of the deleter
        });
    }
}
