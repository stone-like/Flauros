<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\ModelAndRepository\Products\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Gloudemans\Shoppingcart\Exceptions\InvalidRowIDException;



class CartTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_can_add_cart(){
        $quantity = 1;
        $this->cartRepo->addCartToList($this->product,$quantity);
        $cartlist = $this->cartRepo->getCartItems();
        foreach($cartlist as $cart){
            $this->assertEquals($this->product->price,$cart->price);
            $this->assertEquals($this->product->name,$cart->name);
            $this->assertEquals($quantity,$cart->qty);
        }
    }
      /** @test */
      public function it_can_update_cart(){
        $quantity = 1;
        $cart = $this->cartRepo->addCartToList($this->product,$quantity);
        dump($cart);
        $newQuantity = 2;
        $this->cartRepo->updateQuantity($cart->rowId,$newQuantity);
        $cartlist = $this->cartRepo->getCartItems();

        foreach($cartlist as $cart){
            $this->assertEquals($newQuantity,$cart->qty);
        }
    }
     /** @test */
     public function it_can_remove_cart(){
        $quantity = 1;
        $this->cartRepo->addCartToList($this->product,$quantity);
        $diffProduct = factory(Product::class)->create();
        $this->cartRepo->addCartToList($diffProduct,$quantity);

        $this->assertCount(2,$this->cartRepo->getCartItems());
        $this->cartRepo->clearCart();
        $this->assertCount(0,$this->cartRepo->getCartItems());    
    }
    /** @test */
    public function it_can_remove_specific_cart(){
        $quantity = 1;
        $this->cartRepo->addCartToList($this->product,$quantity);
        $diffProduct = factory(Product::class)->create();
        $cart= $this->cartRepo->addCartToList($diffProduct,$quantity);

        $this->assertCount(2,$this->cartRepo->getCartItems());
        $this->cartRepo->removeCart($cart->rowId);
        $this->assertCount(1,$this->cartRepo->getCartItems());    
    }
    /** @test */
    public function it_errors_when_invalid_rowId_removed(){
        $this->expectException(InvalidRowIDException::class);
        $quantity = 1;
        $this->cartRepo->addCartToList($this->product,$quantity);
        

        $this->cartRepo->removeCart("invalid");
           
    }

   /** @test */
   public function it_can_store_to_database(){
    $user = $this->signIn();
    $quantity = 1;
    $this->cartRepo->addCartToList($this->product,$quantity);
    $this->cartRepo->storeToDatabase($user->name);
    //instanceはcartにある通りconst DEFAULT_INSTANCE = 'default';
    $this->assertDatabaseHas("shoppingcart",[
        "identifier" => $user->name,
        "instance" => "default",
        "content" => serialize($this->cartRepo->getCartItems())
    ]);
   }

    /** @test */
   public function it_can_restore_from_database(){
    $user = $this->signIn();
    $quantity = 1;
    $this->cartRepo->addCartToList($this->product,$quantity);
    $this->cartRepo->storeToDatabase($user->name);
    $this->cartRepo->clearCart();

    $this->assertCount(0,$this->cartRepo->getCartItems()); 
    $this->cartRepo->restoreFromDatabase($user->name);
    //一回クリアして、戻ってるかチェック
    $this->assertCount(1,$this->cartRepo->getCartItems()); 
}


    /** @test */
    public function it_can_erase_from_database(){
        $user = $this->signIn();
        $quantity = 1;
        $this->cartRepo->addCartToList($this->product,$quantity);
        $this->cartRepo->storeToDatabase($user->name);
        $this->cartRepo->eraseDatabase($user->name);
        $this->assertDatabaseMissing("shoppingcart",[
            "identifier" => $user->name,
            "instance" => "default",
            "content" => serialize($this->cartRepo->getCartItems())
        ]);
        
    }
    //totalは税とdiscountと送料を足したやつにしたい,送料だけは自分でtotalに送ってあげる必要がある
    /** @test */
   public function it_can_return_total(){
      $user = $this->signIn();
      $quantity = 1;
      $this->cartRepo->setShippingFee(500);
      $this->cartRepo->setGlobalDisCount(10);//この時点より後のitemも影響を受ける
      $this->cartRepo->addCartToList($this->product,$quantity);
      $differentProduct = factory(Product::class)->create([
          "price"=>700
      ]);
      $diffQuantity=2;
      $this->cartRepo->addCartToList($differentProduct,$diffQuantity);
      
      $this->assertEquals("1,896",$this->cartRepo->getTotal()); 
     }
    
   /** @test */
   public function it_can_return_subtotal(){
    $user = $this->signIn();
    $quantity = 1;
    $this->cartRepo->addCartToList($this->product,$quantity);
    $differentProduct = factory(Product::class)->create([
        "price"=>700
    ]);
    $diffQuantity=2;
    $this->cartRepo->addCartToList($differentProduct,$diffQuantity);
    
    $this->assertEquals("1,410",$this->cartRepo->getSubTotal()); 
   }
   
   //商品をすべて足した税の部分
   /** @test */
   public function it_can_return_tax(){
    $user = $this->signIn();
    $quantity = 1;
    $this->cartRepo->addCartToList($this->product,$quantity);
    $differentProduct = factory(Product::class)->create([
        "price"=>700
    ]);
    $diffQuantity=2;
    $this->cartRepo->addCartToList($differentProduct,$diffQuantity);
    
    $this->assertEquals("141",$this->cartRepo->getTax()); 
   }
   /** @test */
   public function it_can_get_discount(){
    $user = $this->signIn();
    $quantity = 1;
    //discountは～%offのことなので５０を入れたら50%off
    $this->cartRepo->setGlobalDisCount(10);//この時点より後のitemも影響を受ける
    $this->cartRepo->addCartToList($this->product,$quantity);
    $differentProduct = factory(Product::class)->create([
        "price"=>700
    ]);
    $diffQuantity=2;
    $this->cartRepo->addCartToList($differentProduct,$diffQuantity);
    
    $this->assertEquals("141",$this->cartRepo->getDiscount()); 
   }

   /** @test */
   public function it_can_return_specific_cartitem(){
    $user = $this->signIn();
    $quantity = 1;
    $cart = $this->cartRepo->addCartToList($this->product,$quantity);
    $cartitem = $this->cartRepo->getItem($cart->rowId);
    $this->assertEquals($this->product->name,$cartitem->name);
   }
  
}