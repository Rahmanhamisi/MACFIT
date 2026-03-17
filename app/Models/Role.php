<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable=[
        'name', 'description'
        ];
    public function users()
    {
         $this->hasMany(User::class);
}
}