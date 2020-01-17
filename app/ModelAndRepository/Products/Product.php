<?php

namespace App\ModelAndRepository\Products;

use Illuminate\Database\Eloquent\Model;
use App\ModelAndRepository\Traits\Requestable;
use App\ModelAndRepository\Categories\Category;

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
