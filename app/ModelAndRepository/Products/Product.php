<?php

namespace App\ModelAndRepository\Products;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\ModelAndRepository\Orders\Order;
use App\ModelAndRepository\Traits\Requestable;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use App\ModelAndRepository\Categories\Category;
use App\ModelAndRepository\ProductImages\ProductImages;

class Product extends Model implements Buyable
{
    use Requestable;

    protected $fillable = [
        "name",
        "slug",
        "description",
        "image",
        "price",
        "quantity",
        "weight",
        "status"
    ];

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
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


    public function getBuyableIdentifier($options = null) {
        return $this->id;
    }
    public function getBuyableDescription($options = null) {
        return $this->name;
    }
    public function getBuyablePrice($options = null) {
        return $this->price;
    }
    //weightはないけどbuyableのために要るみたい
    public function getBuyableWeight($options = null){
        return $this->weight;
    }

   
}
