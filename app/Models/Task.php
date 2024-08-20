<?php

namespace App\Models;

use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([TaskObserver::class])]
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'deadline',
    ];
    protected $casts    = [
        'deadline' => 'datetime:Y-m-d H:00',
    ];
    const PRIORITY_LOW     = 1;
    const PRIORITY_MEDIUM  = 2;
    const PRIORITY_UP      = 3;
    const STATUS_DOING     = 1;
    const STATUS_POSTPONED = 2;
    const STATUS_DONE      = 3;
}
