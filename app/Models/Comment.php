<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildParent;
use App\Models\Doctor;
use App\Models\Reply;



class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'child_parent_id',
        'doctor_id',
        'post_id'
    ];

    public function child_parent()
    {
        return $this->belongsTo(ChildParent::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

}
