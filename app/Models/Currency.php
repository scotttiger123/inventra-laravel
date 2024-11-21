<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory, SoftDeletes;

    // The table associated with the model (optional if it matches the class name in plural)
    protected $table = 'currencies';

    // The attributes that are mass assignable
    protected $fillable = [
        'name', 
        'code', 
        'symbol', 
        'exchange_rate',
        'created_by', // Optional if you track the creator
    ];

    // The attributes that should be mutated to dates
    protected $dates = ['deleted_at'];

    /**
     * Define relationships if needed
     * Example: Created by a user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Example: If a currency is associated with other entities (e.g., transactions)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'currency_id');
    }
}
