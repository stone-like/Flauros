<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\SendOrderCompletedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function can_clear_cart_when_order_completed(){
        $this->withoutExceptionHandling();
          $data=[
            "cartlist"=>$this->cartlist,
            "address_id"=>$this->address->id,
            "order_status_id"=>$this->orderStatus->id,
            "tracking_number"=>$this->trackNumber
          ];
          $this->post("/api/orders",$data);
         
          $count = $this->cartRepo->getItemCount();
          $this->assertEquals(0,$count);
    }

    /** @test */
    public function mail_send_when_order_completed(){
        $this->withoutExceptionHandling();

          Mail::fake();
          $data=[
            "cartlist"=>$this->cartlist,
            "address_id"=>$this->address->id,
            "order_status_id"=>$this->orderStatus->id,
            "tracking_number"=>$this->trackNumber
          ];
          $this->post("/api/orders",$data);
          Mail::assertSent(SendOrderCompletedMail::class);
    }
}
