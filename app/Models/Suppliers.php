<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suppliers extends Model
{
    use SoftDeletes;
    
    protected $table = 'suppliers';

    protected $dates = ['deleted_at'];

    protected $guarded = [];

}
