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

    protected $fillable = ['name', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class)
            ->withPivot('subsection')
            ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getImageUrl($filename = null)
    {
        $image = $filename
            ? $this->images()->where('image_path', 'like', "%/{$filename}")->first()
            : $this->images()->first();

        if (!$image) {
            return null;
        }

        return asset("storage/{$image->image_path}");
    }
}
