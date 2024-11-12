<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
      
      use HasFactory, SoftDeletes;

      protected $fillable = [
        
        'name',
        'location',
        'manager_name',
        'contact_number',
        'created_by',
        'updated_by',
        'deleted_by',
     ];

      // Relationships to User
      public function creator()
      {
          return $this->belongsTo(User::class, 'created_by');
      }
  
      public function updater()
      {
          return $this->belongsTo(User::class, 'updated_by');
      }
  
      public function deleter()
      {
          return $this->belongsTo(User::class, 'deleted_by');
      }
}

    
