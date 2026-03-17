<?php

namespace App\Models;

use illuminate\support\Facades\Hash;
use App\Models\Role;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'role_id',
        'phoneNumber',
        'gymLocation',
        'gender',
        'dob',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'is_active' => 'boolean',
        ];
    }
    public function role()
    {
       return $this->belongsTo(Role::class);
    }

    public function abilities()
    {
        return [
            'admin' => $this->role_id == "1",
            'trainer' => $this->role_id == "2",
            'user' => $this->role_id == "3",
            'staff' => $this->role_id == "4",
        ];
    }
    public function isAdmin()
    {
        // Implement your notification logic here, such as sending an email or storing the notification in the database.
    }
}
