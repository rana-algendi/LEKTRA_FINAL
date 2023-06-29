<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildParent;


class hospital extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'about',
        'address',
        'phone',
        'image',
        'child_parent_id'
    ];

    public function child_parent()
    {
        return $this->belongsTo(ChildParent::class);
    }
}
