<?php
namespace App\ModelAndRepository\Orders\Repository;

use App\ModelAndRepository\Orders\Order;
use App\ModelAndRepository\Products\Product;

interface OrderRepositoryInterface{
    public function createOrder(array $cartlist,int $address_id,int $order_status_id,int $trackingNumber):Order;
    public function attachProduct(Order $order,Product $product);
}