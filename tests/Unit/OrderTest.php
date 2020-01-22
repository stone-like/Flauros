<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\ModelAndRepository\Addresses\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ModelAndRepository\OrderStatuses\OrderStatus;



class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void{
        parent::setUp();
        $this->user = $this->signIn();
        $this->address = factory(Address::class)->create([
            "user_id"=>$this->user->id
        ]);
        $this->orderStatus = factory(OrderStatus::class)->create([]);
        $this->trackNumber=421;

        $this->addCart();
        $this->cartlist =  $this->cartRepo->getTransfromedCartItems();
    }
    
    /** @test */
    public function it_can_order(){
        

        $this->orderRepo->createOrder($this->cartlist,$this->address->id,$this->orderStatus->id,$this->trackNumber);
        $this->assertDatabaseHas("orders",["tracking_number"=>$this->trackNumber]);
    }
    /** @test */
    public function it_can_attach_order_to_product(){
        $this->addCart();//合計二つcartにある

        //controllerから返していないので、modelのまま、よって小分けにしてrepositoryのmethodを実行できる
        $order = $this->orderRepo->createOrder($this->cartlist,$this->address->id,$this->orderStatus->id,$this->trackNumber);
        foreach($this->cartlist["cartitems"]->toArray() as $item){
            $product = $this->proRepo->findProductBySlug(Str::slug($item["name"]));
            $this->orderRepo->attachProduct($order,$product);
         }
      
        $this->assertCount(2,$order->fresh()->products()->get());
    }
    
}
