<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_schedules extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_day', 
        'from', 
        'to',
        'class_id',
    ];
}
