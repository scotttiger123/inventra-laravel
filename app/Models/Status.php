<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    
    protected $table = 'statuses'; 

    
    protected $fillable = ['status_name']; 

    
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'status', 'id'); 
    }
}
