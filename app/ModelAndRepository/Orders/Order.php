<?php

namespace App\ModelAndRepository\Orders;

use App\ModelAndRepository\OrderStatuses\OrderStatus;
use App\ModelAndRepository\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        "address_id",
        "order_status_id",
        "user_id",
        "total",
        "discount",
        "shipping_fee",
        "payment",
        "tracking_number"
    ];

    public function orderstatus(){
        return $this->belongsTo(OrderStatus::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
