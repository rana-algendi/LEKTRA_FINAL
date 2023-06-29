<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildParent;
use App\Models\Doctor;


class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'reply',
        'child_parent_id',
        'post_id',
        'doctor_id',
        'comment_id'

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
