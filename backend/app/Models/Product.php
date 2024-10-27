<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    protected $fillable = ['name', 'description'];

//    public function category()
//    {
//        return $this->belongsTo(Category::class);
//    }

    public function skus()
    {
        return $this->hasMany(SKU::class);
    }

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class)
            ->withPivot('subsection')
            ->withTimestamps();
    }
}
