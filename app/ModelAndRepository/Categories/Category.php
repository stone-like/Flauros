<?php

namespace App\ModelAndRepository\Categories;

use App\ModelAndRepository\Products\Product;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Category extends Model
{
    use NodeTrait;

    protected $fillable  = [
        "name",
        "slug",
        "image",
        "parent_id"
    ];

    public function products(){
        return $this->belongsToMany(Product::class);
    }
    
}
