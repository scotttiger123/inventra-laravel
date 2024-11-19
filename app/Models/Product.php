<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_code', 
        'product_name',
        'cost',
        'price',
        'initial_stock',
        'image_path',
        'uom',
        'brand_id',
        'category_id',
        'product_details',
        'alert_quantity',
        'tax_id',
        'created_by',
        'parent_user_id', 
    ];

    /**
     * Soft delete column.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Define relationship with the User model (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Define relationship with the User model (editor).
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Define relationship with the Brand model.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Define relationship with the Category model.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define relationship with the Tax model.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    /**
     * Accessor for formatted price (optional, for display purposes).
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Accessor for formatted cost (optional, for display purposes).
     */
    public function getFormattedCostAttribute()
    {
        return '$' . number_format($this->cost, 2);
    }

    /**
     * Scope a query to only include products with low stock.
     */
    public function scopeLowStock($query)
    {
        return $query->where('initial_stock', '<=', 'alert_quantity');
    }

    /**
     * Scope a query to only include products by a specific brand.
     */
    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id'); // Assuming `uom_id` is the foreign key
    }
}
