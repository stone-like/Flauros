<?php
namespace App\ModelAndRepository\ShoppingCarts;



use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager;

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
        $this->shippingFee = $shipping_fee;
    }
    public function getShippingFee():int{
        return $this->shippingFee;
    }

    public function getConn()
    {
        $connectionName = $this->getConnName();
        return app(DatabaseManager::class)->connection($connectionName);
    }
    
    public function getTblName()
    {
        return config('cart.database.table', 'shoppingcart');
    }
    
    public function getConnName()
    {
        $connection = config('cart.database.connection');
        return is_null($connection) ? config('database.default') : $connection;
    }
    
    public function checkIdentifier(string $identifier):bool{
        return $this->getConn()->table($this->getTblName())->where('identifier', $identifier)->exists();
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
