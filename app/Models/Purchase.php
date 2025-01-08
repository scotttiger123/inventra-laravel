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
        'purchase_id',
        'custom_purchase_id', 
        'branch_id', 
        'supplier_id', 
        'purchase_manager_id', 
        'status', 
        'staff_note', 
        'purchase_note', 
        'additional_charges', 
        'discount_type', 
        'discount_amount', 
        'paid', 
        'tax_rat', 
        'purchase_date', 
        'updated_by', 
        'deleted_by', 
        'created_by', 
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

    public function status()
    {
        return $this->belongsTo(Status::class, 'status');
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
