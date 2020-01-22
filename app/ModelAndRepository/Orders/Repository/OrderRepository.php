<?php
namespace App\ModelAndRepository\Orders\Repository;

use App\ModelAndRepository\Orders\Order;
use App\ModelAndRepository\Products\Product;
use App\ModelAndRepository\Orders\Repository\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface{
    //cartlistは [
        //     "subtotal" => $subtotal,
        //     "cartCount" => $cartCount,
        //     "tax" => $tax,
        //     "discount" => $discount,
        //     "shippingFee" => $shippingFee,
        //     "total" => $total,
        //     "cartitems" => $cartitems
        // ];でもこの説明を入れるくらいなら型つくったほうがよさそう？
    public function createOrder(array $cartlist,int $address_id,int $order_status_id,int $trackingNumber):Order{
        //adress作成とかtrackingnumberはここの前でやる
         $order = auth()->user()->orders()->create([
             "address_id" => $address_id,
             "order_status_id" => $order_status_id,
             "total" => $cartlist["subtotal"]+$cartlist["tax"],
             "discount" => $cartlist["discount"],
             "shipping_fee" => $cartlist["shippingFee"],
             "payment" => $cartlist["total"],
             "tracking_number" => $trackingNumber
         ]);
         return $order;
    }
    //本当はfindでorderidからとってきたほうがいいんだけど、もうrepositoryはテストのしやすさとレイヤ分けにしか使ってないのでmodelを渡してしまっている
    public function attachProduct(Order $order,Product $product){
            $order->products()->attach($product);
    }
    

}