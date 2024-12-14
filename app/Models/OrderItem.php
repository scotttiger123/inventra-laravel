<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'uom_id',
        'quantity',
        'unit_price',
        'discount_type',
        'discount_amount',
        'exit_warehouse', 
    ];

    /**
     * Define relationship with the Order model.
     */
    

    public function order()
    {
        return $this->belongsTo(Order::class, 'custom_order_id', 'custom_order_id');
    }

    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'exit_warehouse'); 
    }

    /**
     * Define relationship with the Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
