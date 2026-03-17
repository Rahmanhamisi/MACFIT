<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;

class Bundle extends Model
{
    protected $fillable=[
        'name',
        'start_time',
        'duration',
        'value',
        'description',
        'category_id',

        ];
}
