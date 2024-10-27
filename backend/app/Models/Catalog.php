<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_active'];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('subsection')
            ->withTimestamps();
    }
}
