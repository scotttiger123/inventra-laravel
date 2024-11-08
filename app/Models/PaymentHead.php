<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentHead extends Model
{
    use HasFactory, SoftDeletes;

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'name',  // Name of the payment head (e.g., Customer, Supplier, etc.)
        'created_by', // User who created the payment head
        'updated_by', // User who last updated the payment head
    ];

    // Define the relationships to other models (if any)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Soft delete (if necessary)
    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
