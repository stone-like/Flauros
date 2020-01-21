<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Gloudemans\Shoppingcart\CartItem;
use App\ModelAndRepository\ShoppingCarts\Requests\AddCartRequest;
use App\ModelAndRepository\ShoppingCarts\Transforms\CartTransform;
use App\ModelAndRepository\ShoppingCarts\Requests\RemoveCartRequest;
use App\ModelAndRepository\ShoppingCarts\Requests\UpdateQuantityRequest;
use App\ModelAndRepository\Products\Repository\ProductRepositoryInterface;
use App\ModelAndRepository\ShoppingCarts\Repository\CartRepositoryInterface;


class CartController extends Controller
{
    protected $cartRepo;
    protected $proRepo;

    public function __construct(CartRepositoryInterface $cartRepo,ProductRepositoryInterface $proRepo)
    {
        $this->cartRepo = $cartRepo;
        $this->proRepo = $proRepo;
    }
    //loginしていたらadd,update,clear,removeで変更が加わるのでstoreでdatabaseに永続化
    public function addCartToList(AddCartRequest $request):array{
        $product = $this->proRepo->findProductById($request->product_id);
        $cartItem = $this->cartRepo->addCartToList($product,$request->quantity);
        $count = $this->cartRepo->getItemCount();
        return CartTransform::AddTransform($cartItem->subtotal,$count,$product->name,$cartItem->rowId);
    }

    public function updateQuantity(UpdateQuantityRequest $request):array{
        $cartItem = $this->cartRepo->updateQuantity($request->rowId,$request->quantity);
        $subtotal = $this->cartRepo->getSubtotal();
       
        return CartTransform::UpdateTransform($cartItem->rowId,$cartItem->qty,(int)$cartItem->price,$subtotal);
    }

    public function clearCart(){
        return $this->cartRepo->clearCart();
    }
    public function removeCart(RemoveCartRequest $request):array{
        //front用にrowIDとproductNameが欲しい
        $cartitem = $this->cartRepo->getItem($request->rowId);
        $this->cartRepo->removeCart($request->rowId);
        
        return [
            "rowId"=>$request->rowId,
            "productName" => $cartitem->name
        ];
    }
    //トップメニューでもカートの個数表示が欲しいからログインと同時に取ってこなきゃいけなさそう
    //getItemsの時loginしていたらdatabaseから一旦とってくる
    public function getCartItems():array{
         $cartItems = $this->cartRepo->getCartItems();
         $subtotal = $this->cartRepo->getSubtotal();
         $cartCount = count($cartItems);
         return CartTransform::CartListTransform($cartItems,$subtotal,$cartCount);
    }
    //cartからorderの時はeraseDatabaseする、orderControllerのほうに置くかも

    
}
