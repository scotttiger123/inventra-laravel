<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        
        'payable_id', // ID of the related payable (customer or supplier)
        'payable_type', // Type of payable (e.g., Customer or Supplier)
        'amount', // Payment amount
        'status', // Payment status (pending, completed, cancelled)
        'payment_type', // Type of payment (credit or debit)
        'invoice_id', // ID of related invoice (if applicable)
        'payment_head', // Payment head (customer or supplier)
        'payment_method',
        'updated_by', // User who last updated the payment
        'deleted_by', // User who soft deleted the payment
        'created_by', // The user who entered the payment
    ];

    /**
     * Define relationship with the User model (creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Polymorphic relationship for customer or supplier.
     */
    public function payable()
    {
        return $this->morphTo(); // This will relate the payment to a customer or supplier
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
     * Automatically track who updated or deleted the payment.
     */
    protected static function booted()
    {
        static::updating(function ($payment) {
            $payment->updated_by = auth()->id(); // Capture the user ID of the updater
        });

        static::deleting(function ($payment) {
            $payment->deleted_by = auth()->id(); // Capture the user ID of the deleter
        });
    }
}
