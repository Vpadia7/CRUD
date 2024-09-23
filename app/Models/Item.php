<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Specify the table if it does not follow the default naming convention
    protected $table = 'items';

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'description',
    ];

    protected $dates = ['deleted_at'];
}
