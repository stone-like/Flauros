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
    public function storeToDatabase(string $username){
        $this->cartRepo->storeToDatabase($username);
    }
    public function restoreFromDatabase(string $username){
        $this->cartRepo->restoreFromDatabase($username);
    }
    public function mergeTodatabase(string $username){
        $this->cartRepo->mergeToDatabase($username);
    }
    public function processDatabase(){
        if($this->cartRepo->checkIdentifier(auth()->user()->name)){
            //どうやらstoreはupdateはできないみたいなので既に存在していたらeraseしてからstore
            $this->cartRepo->eraseDatabase(auth()->user()->name);
        }
        $this->storeToDatabase(auth()->user()->name);
    }
    //loginしていたらadd,update,clear,removeで変更が加わるのでstoreでdatabaseに永続化
    public function addCartToList(AddCartRequest $request):array{
        $product = $this->proRepo->findProductById($request->product_id);
        $cartItem = $this->cartRepo->addCartToList($product,$request->quantity);
        $count = $this->cartRepo->getItemCount();
        
        if(auth()->check()){
            $this->processDatabase();
        }
        
        return CartTransform::AddTransform($cartItem->subtotal,$count,$product->name,$cartItem->rowId);
    }

    public function updateQuantity(UpdateQuantityRequest $request):array{
        $cartItem = $this->cartRepo->updateQuantity($request->rowId,$request->quantity);
        $subtotal = $this->cartRepo->getSubtotal();

        if(auth()->check()){
            $this->processDatabase();
        }
       
        return CartTransform::UpdateTransform($cartItem->rowId,$cartItem->qty,(int)$cartItem->price,$subtotal);
    }

    public function clearCart(){
        $this->cartRepo->clearCart();
        if(auth()->check()){
            $this->cartRepo->eraseDatabase(auth()->user()->name);
        }
    }
    public function removeCart(RemoveCartRequest $request):array{
        //front用にrowIDとproductNameが欲しい
       
        $cartitem = $this->cartRepo->getItem($request->rowId);
        $this->cartRepo->removeCart($request->rowId);
        

        if(auth()->check()){
            $this->processDatabase();
        }
        
        return [
            "rowId"=>$request->rowId,
            "productName" => $cartitem->name
        ];
    }
    //トップメニューでもカートの個数表示が欲しいからログインと同時に取ってこなきゃいけなさそう
    //getItemsの時loginしていたらdatabaseから一旦とってくる
    public function getCartItems():array{
        if(auth()->check()){
            $this->restoreFromDatabase(auth()->user()->name);
        }

         return $this->cartRepo->getTransfromedCartItems();
    }
    //cartからorderの時はeraseDatabaseする、orderControllerのほうに置くかも

    
}
