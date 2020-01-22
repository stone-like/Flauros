<?php
namespace App\ModelAndRepository\ShoppingCarts\Repository;



use Illuminate\Support\Collection;
use Gloudemans\Shoppingcart\CartItem;

use App\ModelAndRepository\Products\Product;
use App\ModelAndRepository\ShoppingCarts\ShoppingCart;
use App\ModelAndRepository\ShoppingCarts\Transforms\CartTransform;

class CartRepository implements CartRepositoryInterface{
    protected $cart;

    public function __construct()
    {
        $this->cart = new ShoppingCart;
    }


    public function addCartToList(Product $product,int $quantity,$options=[]):CartItem{
           return $this->cart->add($product,$quantity,$options);
    }
    public function updateQuantity(string $rowId,int $quantity):CartItem{
        return $this->cart->update($rowId,$quantity);
    }
    public function clearCart(){
        $this->cart->destroy();
    }
    public function removeCart(string $rowId){
        $this->cart->remove($rowId);
    }
    public function storeToDatabase(string $username){
       $this->cart->store($username);
    }
    public function  restoreFromDatabase(string $username){
        $this->cart->restore($username);
      
    }
    public function  eraseDatabase(string $username){
      
        $this->cart->erase($username);
    }
    public function mergeToDatabase(string $username){
        $this->cart->merge($username);
    }
    public function checkIdentifier(string $username):bool{
        return $this->cart->checkIdentifier($username);
    }


    
    public function getTotal():string{
        
       return $this->cart->total();
    }
    public function getSubTotal():string{
        return $this->cart->subtotal();

    }
    public function getTax():string{
        return $this->cart->tax();

    }
    public function setGlobalDiscount(int $discount){
       return $this->cart->setGlobalDiscount($discount);
    }
    public function getDiscount():string{
        return $this->cart->discount();
       
    }
    public function setShippingFee(int $shipping_fee){
           $this->cart->setShippingFee($shipping_fee);
    }
    public function getShippingFee():int{
         return $this->cart->getShippingFee();
    }
    public function getItemCount():int{
        return $this->cart->count();
    }
    public function getItem(string $rowId):CartItem{
        return $this->cart->get($rowId);
    }






    //Eloquentじゃない方のCollection
    public function getCartItems():Collection{
         return $this->cart->content();
    }

    public function getTransfromedCartItems():array{
        $cartItems = $this->getCartItems();
        $subtotal = $this->getSubtotal();
        $cartCount = count($cartItems);
        $tax = $this->getTax();
        $discount = $this->getDiscount();
        $shippingFee = $this->getShippingFee();
        $total = $this->getTotal($shippingFee);

        return CartTransform::CartListTransform($cartItems,$subtotal,$cartCount,$tax,$discount,$shippingFee,$total);
    }
}