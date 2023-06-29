<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildParent;
use App\Models\Doctor;
use App\Models\Comment;
use App\Models\Like;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'child_parent_id',
        'doctor_id',
        'image'
    ];

    public function child_parent()
    {
        return $this->belongsTo(ChildParent::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function reply()
    {
        return $this->hasMany(Reply::class);
    }
}
