<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseItem extends Model
{
    use HasFactory;

    // Specify the table name if it's not using the default plural convention
    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'product_id',
        'uom_id',
        'quantity',
        'unit_price',
        'discount_type',
        'discount_amount',
        'cost_price', // Added cost_price
        'amount',
        'inward_warehouse_id',
    ];

    protected static function booted()
    {
        static::creating(function ($purchaseItem) {
            // Set inward_warehouse_id to 0 if it's null
            $purchaseItem->inward_warehouse_id = $purchaseItem->inward_warehouse_id ?? 0;
        });
    }
    /**
     * Define relationship with the Purchase model.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'custom_purchase_id', 'custom_purchase_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'inward_warehouse_id'); 
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id'); // Adjust the foreign key if necessary
    }

    /**
     * Define relationship with the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Define relationship with the Unit of Measure model.
     */
    public function uom()
    {
        return $this->belongsTo(Unit::class, 'uom_id');
    }
}

