<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'po_box',
        'initial_balance',
        'tax_number',
        'discount_type',
        'discount_value',
    ];
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class); // Assuming the 'supplier_id' foreign key is used
    }
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
     * Soft delete column.
     * @var array
     */
    protected $dates = ['deleted_at'];

}
