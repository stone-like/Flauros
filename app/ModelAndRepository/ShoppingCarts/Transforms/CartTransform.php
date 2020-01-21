<?php

namespace App\ModelAndRepository\ShoppingCarts\Transforms;

use Illuminate\Support\Collection;

 


class CartTransform {
    public static function AddTransform(string $subtotal,int $cartCount,string $productName,string $rowId):array{
           //今追加したカートと、追加したproductの情報と、カートの点数がいる
           return [
               "subtotal" => $subtotal,
               "cartCount" => $cartCount,
               "productName" => $productName,
               "rowId" => $rowId
           ];
    }
    public static function UpdateTransform(string $rowId,int $quantity,int $price,string $subtotal):array{
        return [
             "rowId" => $rowId,
             "quantity" => $quantity,
             "price" => $price,
             "subtotal" => $subtotal
        ];
    }
    public static function CartListTransform(Collection $cartitems,string $subtotal,int $cartCount):array{
        //今追加したカートと、追加したproductの情報と、カートの点数がいる
        return [
            "subtotal" => $subtotal,
            "cartCount" => $cartCount,
            "cartitems" => $cartitems
        ];
 }
}