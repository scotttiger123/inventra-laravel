<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'payment_date' => 'datetime',
    ];
    
    protected $fillable = [
        
        'payable_id', 
        'payable_type', 
        'amount', 
        'status', // Payment status (pending, completed, cancelled)
        'payment_type', // Type of payment (credit or debit)
        'invoice_id', // ID of related invoice (if applicable)
        'payment_head', // Payment head (customer or supplier)
        'payment_method',
        'account_id',
        'payment_date',
        'note',
        'updated_by', 
        'deleted_by', 
        'created_by', 
    ];



    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'payable_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'payable_id');
    }

    /**
     * Soft delete column.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Automatically track who updated or deleted the payment.
     */

     public function paymentHead()
     {
         return $this->belongsTo(PaymentHead::class, 'payment_head');
     }

     
    protected static function booted()
    {
        static::updating(function ($payment) {
            $payment->updated_by = auth()->id(); // Capture the user ID of the updater
        });

        static::deleting(function ($payment) {
            $payment->deleted_by = auth()->id(); // Capture the user ID of the deleter
        });
    }


    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }


    
}
