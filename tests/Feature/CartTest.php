<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\ModelAndRepository\Products\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function user_can_add_cart(){
       $data = [
           "product_id" => $this->product->id,
           "quantity" => 1
       ];
       $postedCart = $this->post("/api/carts",$data);
       $this->assertEquals(10,$postedCart["subtotal"]);
       $this->assertEquals(1,$postedCart["cartCount"]);
       $this->assertEquals($this->product->name,$postedCart["productName"]);
    }
    /** @test */
    public function user_can_get_cartitems(){
        $diffProduct = factory(Product::class)->create([
            "price" => 700
        ]);
        $data1 = [
            "product_id" => $this->product->id,
            "quantity" => 1
        ];
        $data2= [
            "product_id" => $diffProduct->id,
            "quantity" => 1
        ];
        $datum = [
            $this->product,
            $diffProduct
        ];
        
        $this->post("/api/carts",$data1);
        $this->post("/api/carts",$data2);
       
        $cartlist = $this->get("/api/carts");

        $this->assertEquals(710,$cartlist["subtotal"]);
       $this->assertEquals(2,$cartlist["cartCount"]);
       foreach(array_map(null,$cartlist["cartitems"],$datum) as [$val1,$val2]){
        $this->assertEquals($val1["price"],$val2->price);
       }
     }
     //updateとdeleteは戻り値をfront側で反映させてあげるためにcartidをとってくればいいかな？,小計もfront側でいじる必要がある
     /** @test */
    public function user_can_update_quantity(){
        $diffProduct = factory(Product::class)->create([
            "price" => 700
        ]);
        $data1 = [
            "product_id" => $this->product->id,
            "quantity" => 1
        ];
        $data2 = [
            "product_id" => $diffProduct->id,
            "quantity" => 1
        ];
        
        $this->post("/api/carts",$data1);
        $postedCart = $this->post("/api/carts",$data2);
       
        $data = [
            "rowId" => $postedCart["rowId"],
            "quantity" => 2
        ];
        //updateしたCartのrowIdとquantityが欲しい
        $updateData = $this->patch("/api/carts",$data);
        $this->assertEquals($postedCart["rowId"],$updateData["rowId"]); 
        $this->assertEquals(1400,$updateData["price"]*$updateData["quantity"]);
        $this->assertEquals("1,410",$updateData["subtotal"]);
        
       
     }
      /** @test */
    public function user_can_delete_quantity(){
      
        $data = [
            "product_id" => $this->product->id,
            "quantity" => 1
        ];
       
        
        $postedCart = $this->post("/api/carts",$data);
        
        
       $data = [
           "rowId" =>$postedCart["rowId"]
       ];
        
        $deleteData = $this->delete("/api/carts",$data);
        $this->assertEquals($postedCart["rowId"],$deleteData["rowId"]); 
        $this->assertEquals( $this->product->name,$deleteData["productName"]);
        
     }
      /** @test */
      public function guest_can_not_restore_from_database(){
        
        $data = [
            "product_id" => $this->product->id,
            "quantity" => 1
        ];
       
        $postedCart = $this->post("/api/carts",$data);
        session()->forget("cart.default");
        $cartlist = $this->get("/api/carts");
        $this->assertCount(0,$cartlist["cartitems"]);
        
       //sessionから値をdeleteしてgetItemできればok
               
     }
      /** @test */
      public function loggedIn_user_can_delete_and_restore_from_database(){
        $this->signIn();
        $diffProduct = factory(Product::class)->create();
        $data1 = [
            "product_id" => $this->product->id,
            "quantity" => 1
        ];
        $data2 = [
            "product_id" => $diffProduct->id,
            "quantity" => 1
        ];
       
        $postedCart = $this->post("/api/carts",$data1);
        $this->post("/api/carts",$data2);
        
        $data = [
            "rowId"=>$postedCart["rowId"]
        ];
        $this->delete("/api/carts",$data);
        session()->forget("cart.default");
        $cartlist = $this->get("/api/carts");
        $this->assertCount(1,$cartlist["cartitems"]);
        
       //sessionから値をdeleteしてgetItemできればok
               
     }

      /** @test */
      public function loggedIn_user_can_update_restore_from_database(){
        $this->signIn();
        
        $data = [
            "product_id" => $this->product->id,
            "quantity" => 1
        ];
        
       
        $postedCart = $this->post("/api/carts",$data);
        
        
        $data = [
            "rowId"=>$postedCart["rowId"],
            "quantity"=>2
        ];
        $this->patch("/api/carts",$data);
        session()->forget("cart.default");
        $cartlist = $this->get("/api/carts");
        dump($cartlist);
        $this->assertEquals(2,$cartlist["cartitems"][$postedCart["rowId"]]["qty"]);
        
       
               
     }
      /** @test */
      public function loggedIn_user_can_erase_database(){
        $this->signIn();
        $data = [
            "product_id" => $this->product->id,
            "quantity" => 1
        ];
       
        $postedCart = $this->post("/api/carts",$data);
        $this->delete("/api/clearcart");
        session()->forget("cart.default");
        $cartlist = $this->get("/api/carts");
        $this->assertCount(0,$cartlist["cartitems"]);
        
       //sessionから値をdeleteしてgetItemできればok
               
     }
     /** @test */
     public function databse_clear_when_cart_count_is_0(){
         $this->withoutExceptionHandling();
        $this->signIn();
        $data = [
            "product_id" => $this->product->id,
            "quantity" => 1
        ];
       
        $postedCart = $this->post("/api/carts",$data);
        $data=[
            "rowId" => $postedCart["rowId"]
        ];
        $this->delete("/api/carts",$data);
        session()->forget("cart.default");
        $cartlist = $this->get("/api/carts");
        $this->assertCount(0,$cartlist["cartitems"]);
        
       //sessionから値をdeleteしてgetItemできればok
               
     }
}
