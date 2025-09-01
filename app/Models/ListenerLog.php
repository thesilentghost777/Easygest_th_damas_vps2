<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListenerLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'listener_name',
        'status',
        'message',
        'details',
        'executed_at',
        'execution_time'
    ];

    protected $casts = [
        'details' => 'array',
        'executed_at' => 'datetime',
        'execution_time' => 'float'
    ];
}
