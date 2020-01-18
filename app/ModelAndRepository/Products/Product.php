<?php

namespace App\ModelAndRepository\Products;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\ModelAndRepository\Traits\Requestable;
use App\ModelAndRepository\Categories\Category;
use App\ModelAndRepository\ProductImages\ProductImages;

class Product extends Model
{
    use Requestable;

    protected $fillable = [
        "name",
        "slug",
        "description",
        "image",
        "price",
        "quantity",
        "status"
    ];

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function productimages(){
        return $this->hasMany(ProductImages::class);
    }

    public static function getStatus(int $quantity):string{
        if($quantity === 0){
           return   "sold out";
        }
        
        if($quantity === 1){
            return  "1 item left";
        }

         return $quantity." items left";
    }

   
}
