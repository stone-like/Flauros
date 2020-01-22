<?php

namespace App\ModelAndRepository\Orders;

use App\ModelAndRepository\Users\User;
use Illuminate\Database\Eloquent\Model;
use App\ModelAndRepository\Products\Product;
use App\ModelAndRepository\Addresses\Address;
use App\ModelAndRepository\OrderStatuses\OrderStatus;

class Order extends Model
{
    protected $fillable = [
        "address_id",
        "order_status_id",
        "user_id",
        "total",//subtotal*tax
        "discount",
        "shipping_fee",
        "payment",
        "tracking_number"
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orderstatus(){
        return $this->belongsTo(OrderStatus::class);
    }

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
