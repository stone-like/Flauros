<?php
namespace App\ModelAndRepository\ShoppingCarts;



use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Cart
{
    protected $session;
    protected $events;
    protected $shippingFee;
    
    //Cartをそのまま使ってもいいんだけど、管理のしやすさのため
    public function __construct()
    {
        $this->session = $this->getSession();
        $this->events = $this->getEvents();
        parent::__construct($this->session,$this->events);
        $this->shippingFee = 0;
    }

    public function getSession(){
        return app()->make("session");
    }
    public function getEvents(){
        return app()->make("events");
    }

    public function setShippingFee(int $shipping_fee){
        $this->shippinhFee = $shipping_fee;
    }
    public function getShippingFee():int{
        return $this->shippinhFee;
    }

    //shippingFeeを扱えるようにtotalをoverride
    public function total($decimals=null,$decimalPoint=null,$thousandSeperator = null){
        $content = $this->getContent();

        $total = $content->reduce(function ($total, CartItem $cartItem){
            return $total + ($cartItem->qty * $cartItem->priceTax);
        }, 0);
        $total += $this->getShippingFee();
        return number_Format($total, $decimals, $decimalPoint, $thousandSeperator);
    }
}
