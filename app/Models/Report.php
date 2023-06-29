<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildParent;
use App\Models\Doctor;
use App\Models\Image;

class Report extends Model
{
    
    use HasFactory;

    
    protected $fillable = [
            'image_id',
            'child_parent_id',
            'doctor_id',
            'child_name',
            'child_image',
            'father_name',
            'mother_name',
            'national_id',
            'blood_type',
            'age',
            'birthday',
            'weight',
            'height',
            'started_in',
            'last_time',
            'diagnosis'

    ];

    public function child_parent()
    {
        return $this->belongsTo(ChildParent::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
