<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\Bundle;

class Subscription extends Model
{
    protected $fillable=[
        'user_id',
        'bundle_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
