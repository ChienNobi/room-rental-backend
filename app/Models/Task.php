<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'label',
        'priority',
        'description',
        'start_date',
        'end_date',
        'status_id',
        'user_id',
        'parent_id'
    ];
}
