<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildParent;
use App\Models\Doctor;

class Image extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'descrpition',
        'taken_at',
        'child_parent_id',
        'doctor_id',
        'report_id'

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
