<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Providers\OrderCompleted;
use App\ModelAndRepository\Orders\Order;
use App\ModelAndRepository\Orders\Requests\CreateOrderRequest;
use App\ModelAndRepository\Orders\Repository\OrderRepositoryInterface;
use App\ModelAndRepository\Products\Repository\ProductRepositoryInterface;
use App\ModelAndRepository\ShoppingCarts\Repository\CartRepositoryInterface;

class OrderController extends Controller
{
    protected $orderRepo;
    protected $cartRepo;

    public function __construct(OrderRepositoryInterface $orderRepo,CartRepositoryInterface $cartRepo,ProductRepositoryInterface $proRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->cartRepo = $cartRepo;
        $this->proRepo = $proRepo;
    }
    public function createOrder(CreateOrderRequest $request):Order{
      
        $order = $this->orderRepo->createOrder($request->cartlist,$request->address_id,$request->order_status_id,$request->tracking_number);
        $this->attachOrderToProduct($order,$request->cartlist["cartitems"]->toArray());
        $this->cartRepo->clearCart();
        $this->cartRepo->eraseDatabase(auth()->user()->name);
        event(new OrderCompleted($order,auth()->user()));
        return $order;
    }
    public function attachOrderToProduct(Order $order,array $cartitems){
        foreach($cartitems as $item){
            $product = $this->proRepo->findProductBySlug(Str::slug($item["name"]));
            $this->orderRepo->attachProduct($order,$product);
      }
    }

    
}
