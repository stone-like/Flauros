<?php

namespace App\ModelAndRepository\Products;

use Illuminate\Database\Eloquent\Model;
use App\ModelAndRepository\Categories\Category;

class Product extends Model
{
    protected $fillable = [
        "name",
        "slug",
        "description",
        "image",
        "quantity",
        "status"
    ];

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
}
