<?php
namespace App\ModelAndRepository\ShoppingCarts\Repository;

use Illuminate\Support\Collection;
use Gloudemans\Shoppingcart\CartItem;
use App\ModelAndRepository\Products\Product;


interface CartRepositoryInterface{
    public function addCartToList(Product $product,int $quantity,$options=[]):CartItem;
    public function updateQuantity(string $rowId,int $quantity):CartItem;
    public function clearCart();
    public function removeCart(string $rowId);
    public function storeToDatabase(string $username);
    public function  restoreFromDatabase(string $username);
    public function  eraseDatabase(string $username);
    public function getTotal():string;
    public function getSubTotal():string;
    public function getTax():string;
    public function setGlobalDiscount(int $discount);
    public function setShippingFee(int $shipping_fee);
    public function getShippingFee():int;
    public function getDiscount():string;
    public function getCartItems():Collection;
    public function getItemCount():int;
    public function getItem(string $rowId):CartItem;
}