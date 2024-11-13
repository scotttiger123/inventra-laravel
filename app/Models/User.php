<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 'parent_id','status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'Role'); // 'Role' is the column name in the users table
    }

    // A user can have many child users
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function products()
    {
        return $this->hasMany(Product::class, 'created_by');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
