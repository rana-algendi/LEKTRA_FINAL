<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'child_parent_id',
        'doctor_id',
        'date',
        'day',
        'time',
        'status',
    ];

    public function child_parent()
    {
        return $this->belongsTo(ChildParent::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}

